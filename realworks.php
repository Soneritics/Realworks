<?php
	/**
	 * Cronjob runt elke dag om 9.00 uur.
	 * Cron maakt gebruik van de Realworks koppeling 1.2.
	 */

	ini_set("default_socket_timeout", 1800);
	set_time_limit(8000);

	// Instellingen
	$user = '';
	$password = '';

	// Variabelen initialisatie
	$errors = array();
	$objecten = array();
	$objecten_bouwtypes = array();
	$objecten_bouwtypes_bouwnummers = array();

	// Models invoken
	$object_model = App::invoke('model', 'Object');
	$object_bt_model = App::invoke('model', 'ObjectBouwtype');
	$object_bt_bn_model = App::invoke('model', 'ObjectBouwtypeBouwnummer');
	$route_model = App::invoke('model', 'Route');

	// Aray met types en download URL's
	$download_urls = array
	(
		'0' => 'https://xml-publish.realworks.nl/servlets/ogexport?koppeling=WEBSITE&og=WONEN&user=' . $user . '&password=' . $password,
		'1' => 'https://xml-publish.realworks.nl/servlets/ogexport?koppeling=WEBSITE&og=BOG&kantoor=02171&user=' . $user . '&password=' . $password,
		'2' => 'https://xml-publish.realworks.nl/servlets/ogexport?koppeling=WEBSITE&og=NIEUWBOUW&user=' . $user . '&password=' . $password
	);

	// Temp dir aanmaken
	$dir = TMP . DS . time() . DS;
	mkdir($dir);

	// Foto's verwerken
	function fotos($object_id, $media_lijst)
	{
		// Map aanmaken
		$upl = APP_WEBROOT . DS . 'uploaded' . DS . 'Huizen' . DS;
		if ( !file_exists($upl) )
			mkdir($upl);

		$upl .= $object_id . DS;
		if ( !file_exists($upl) )
			mkdir($upl);

		// Afbeeldingen loopen, downloaden en aan result array toevoegen
		$result = array();

		foreach ( $media_lijst->Media as $media )
		{
			$jpg = str_replace('/', '-', base64_encode((string)$media->MediaUpdate . (string)$media->URL)) . '.jpg';

			if ( !file_exists($upl . $jpg) )
			{
				# Downloaden
				file_put_contents($upl . $jpg, file_get_contents((string)$media->URL));

				# Controleren of afbeelding, dan resizen
				if ( App::invoke('component', 'Image')->isImage($upl . $jpg) )
				{
					list($w, $h) = App::invoke('component', 'Image')->getWH($upl . $jpg, 800);
					$res = App::invoke('component', 'Image')->getThumb(App::invoke('component', 'Image')->getHandle($upl . $jpg), $w, $h);
					imagejpeg($res, $upl . $jpg);
					$res = null;
				}
			}

			# Toevoegen aan download array, indien afbeelding
			if ( App::invoke('component', 'Image')->isImage($upl . $jpg) )
				$result[] = $jpg;
			else
				@unlink($upl . $jpg);
		}

		return base64_encode(serialize($result));
	}

	// Foto's verwerken
	function brochure($object_id, $media_lijst)
	{
		// Map aanmaken
		$upl = APP_WEBROOT . DS . 'uploaded' . DS . 'Brochures' . DS;
		if ( !file_exists($upl) )
			mkdir($upl);

		$upl .= $object_id . DS;
		if ( !file_exists($upl) )
			mkdir($upl);

		// Media loopen, downloaden en aan result array toevoegen
		foreach ( $media_lijst->Media as $media )
			if ( isset($media->Groep) && strtolower((string)$media->Groep) == 'brochure' )
			{
				$pdf = 'Brochure.pdf';

				if ( !file_exists($upl . $pdf) || filemtime($upl . $pdf) < strtotime((string)$media->MediaUpdate) )
					file_put_contents($upl . $pdf, file_get_contents((string)$media->URL));

				return $pdf;
			}

		return '';
	}

	// 360 graden foto ophalen
	function foto360($media_lijst)
	{
		foreach ( $media_lijst->Media as $media )
			if ( isset($media->MediaOmschrijving) )
			{
				$exp = explode(';', (string)$media->MediaOmschrijving);
				if ( count($exp) > 0 )
					foreach ( $exp as $url )
						if ( strpos($url, 'virtueletourzien.nl') !== false )
							return trim($url);
						elseif ( strpos($url, 'renesloot.nl') !== false )
							return trim($url);
						elseif ( strpos($url, 'objectinbeeld.nl') !== false )
							return trim($url);
			}

		return '';
	}

	// Plattegrond ophalen
	function plattegrond($media_lijst)
	{
		foreach ( $media_lijst->Media as $media )
			if ( isset($media->MediaOmschrijving) )
			{
				$exp = explode(';', (string)$media->MediaOmschrijving);
				if ( count($exp) > 0 )
					foreach ( $exp as $url )
						if ( strpos($url, 'pl.an') !== false ||
                             strpos($url, 'draftingfactory') !== false ||
                             strpos($url, 'floorplanner.com') !== false
                         ) {
							return trim($url);
                        }
			}

		return '';
	}

	// XML objecten samenvoegen in geserializede en base6-encode string
	function concat_xml($xml)
	{
		$result = array();

		if ( count($xml) > 0 )
			foreach ( $xml as $s )
				$result[] = (string)$s;

		return base64_encode(serialize($result));
	}

	// Bestanden verwerken
	foreach ( $download_urls as $type => $url )
	{
		// Zip downloaden en uitpakken
		file_put_contents($dir . 'dl.zip', file_get_contents($url));
		exec("unzip -o " . $dir . "dl.zip -d " . $dir);

		// XML bestanden doorlopen
		foreach ( scandir($dir) as $f )
			if ( substr($f, -4) == '.xml' )
			{
				// XML openen
				$simpleXML = simplexml_load_file($dir . $f);

				// Kijken of het projecten (nieuwbouw) of objecten betreft
				if ( isset($simpleXML->Object) )
					foreach ( $simpleXML->Object as $object )
					{
						// Object doorlopen
						$arr = array();

						$arr['object_code'] = (string)$object->ObjectCode;
						$arr['type'] = $type;

						if ( isset($object->ObjectDetails->DatumInvoer) )
							$arr['ingevoerd'] = strtotime((string)$object->ObjectDetails->DatumInvoer);

						if ( isset($object->ObjectDetails->Koop) )
						{
							$arr['koop_huur'] = 0;

							if ( isset($object->ObjectDetails->Koop->Prijsvoorvoegsel) )							$arr['prijsvoorvoegsel'] = (string)$object->ObjectDetails->Koop->Prijsvoorvoegsel;
							if ( isset($object->ObjectDetails->Koop->Koopprijs) )				$arr['prijs'] = (string)$object->ObjectDetails->Koop->Koopprijs;
							if ( isset($object->ObjectDetails->Koop->PrijsSpecificatie->Prijs) )					$arr['prijs'] = (string)$object->ObjectDetails->Koop->PrijsSpecificatie->Prijs;
							if ( isset($object->ObjectDetails->Koop->KoopConditie) )								$arr['prijstype'] = (string)$object->ObjectDetails->Koop->KoopConditie;
						}
						elseif ( $object->ObjectDetails->Huur )
						{
							$arr['koop_huur'] = 1;
							$arr['prijs'] = isset($object->ObjectDetails->Huur->Huurprijs) ? (string)$object->ObjectDetails->Huur->Huurprijs : (string)$object->ObjectDetails->Huur->PrijsSpecificatie->Prijs;
							$arr['prijstype'] = (string)$object->ObjectDetails->Huur->HuurConditie;
						}

						if ( ( isset($object->ObjectDetails->StatusBeschikbaarheid->Status) && !in_array((string)$object->ObjectDetails->StatusBeschikbaarheid->Status, array('Ingetrokken', 'Geveild', 'Verkocht', 'Verhuurd')) ) || ( isset($object->ObjectDetails->Status->StatusType) && !in_array((string)$object->ObjectDetails->Status->StatusType, array('Ingetrokken', 'Geveild', 'Verkocht', 'Verhuurd')) ) )
							$arr['verwijderd'] = '0';
						else
							$arr['verwijderd'] = '1';

						if ( isset($object->Web->KorteOmschrijving) )												$arr['beschrijving_kort'] = (string)$object->Web->KorteOmschrijving;
						if ( isset($object->ObjectDetails->Aanbiedingstekst) )										$arr['beschrijving_lang'] = (string)$object->ObjectDetails->Aanbiedingstekst;
						if ( isset($object->Wonen->Appartement->SoortAppartement) )									$arr['soort'] = (string)$object->Wonen->Appartement->SoortAppartement;
						if ( isset($object->Wonen->Woonhuis->SoortWoning) )											$arr['soort'] = (string)$object->Wonen->Woonhuis->SoortWoning;
						if ( isset($object->ObjectDetails->Bestemming->Hoofdbestemming) )							$arr['soort'] = (string)$object->ObjectDetails->Bestemming->Hoofdbestemming;
						if ( isset($object->ObjectDetails->Bouwvorm) )												$arr['bouwvorm'] = (string)$object->ObjectDetails->Bouwvorm;
						if ( isset($object->ObjectDetails->Status->StatusType) )									$arr['status'] = (string)$object->ObjectDetails->Status->StatusType;
						if ( isset($object->ObjectDetails->OppervlaktePerceel) )									$arr['oppervlakte_perceel'] = $object->ObjectDetails->OppervlaktePerceel;
						if ( isset($object->Wonen->WonenDetails->MatenEnLigging->Inhoud) )							$arr['inhoud'] = (string)$object->Wonen->WonenDetails->MatenEnLigging->Inhoud;
						if ( isset($object->Wonen->WonenDetails->MatenEnLigging->GebruiksoppervlakteWoonfunctie) )	$arr['oppervlakte_wonen'] = (string)$object->Wonen->WonenDetails->MatenEnLigging->GebruiksoppervlakteWoonfunctie;
						if ( isset($object->Wonen->WonenDetails->MatenEnLigging->PerceelOppervlakte) )				$arr['oppervlakte_perceel'] = (string)$object->Wonen->WonenDetails->MatenEnLigging->PerceelOppervlakte;
						if ( isset($object->Wonen->WonenDetails->Bouwjaar->JaarOmschrijving->Jaar) )				$arr['bouwjaar'] = (string)$object->Wonen->WonenDetails->Bouwjaar->JaarOmschrijving->Jaar;
						if ( isset($object->Gebouw->GebouwDetails->Bouwjaar->Periode) )								$arr['bouwjaar'] = (string)$object->Gebouw->GebouwDetails->Bouwjaar->Periode;
						if ( isset($object->Wonen->WonenDetails->Onderhoud->Binnen->Waardering) )					$arr['onderhoud_binnen'] = (string)$object->Wonen->WonenDetails->Onderhoud->Binnen->Waardering;
						if ( isset($object->Wonen->WonenDetails->Onderhoud->Buiten->Waardering) )					$arr['onderhoud_buiten'] = (string)$object->Wonen->WonenDetails->Onderhoud->Buiten->Waardering;

						$arr['openhuis_van'] = isset($object->Web->OpenHuis->Vanaf) ? (string)$object->Web->OpenHuis->Vanaf : '';
						$arr['openhuis_tot'] = isset($object->Web->OpenHuis->Tot) ? (string)$object->Web->OpenHuis->Tot : '';

						if ( isset($object->MediaLijst) )
						{
							$arr['afbeeldingen'] = fotos((string)$object->ObjectCode, $object->MediaLijst);
							$arr['brochure'] = brochure((string)$object->ObjectCode, $object->MediaLijst);
							$arr['foto360'] = foto360($object->MediaLijst);
							$arr['plattegrond'] = plattegrond($object->MediaLijst);
						}

						if ( isset($object->ObjectDetails->Adres->Straatnaam) )
							$adres = $object->ObjectDetails->Adres;
						elseif ( isset($object->ObjectDetails->Adres->Nederlands) )
							$adres = $object->ObjectDetails->Adres->Nederlands;
						else
							$adres = array();

						if ( isset($adres->Straatnaam) )															$arr['straat'] = (string)$adres->Straatnaam;
						if ( isset($adres->Huisnummer) )															$arr['huisnummer'] = (string)$adres->Huisnummer;
						if ( isset($adres->Huisnummer->Hoofdnummer) )												$arr['huisnummer'] = (string)$adres->Huisnummer->Hoofdnummer;

						if ( isset($adres->HuisnummerToevoeging) )
							$arr['huisnummer_toevoeging'] = (string)$adres->HuisnummerToevoeging;
						else
							$arr['huisnummer_toevoeging'] = '';

						if ( isset($adres->Postcode) )																$arr['postcode'] = (string)$adres->Postcode;
						if ( isset($adres->Woonplaats) )															$arr['plaats'] = (string)$adres->Woonplaats;
						if ( isset($adres->Land) )																	$arr['land'] = (string)$adres->Land;

						if ( isset($object->Wonen->WonenDetails->MatenEnLigging->Liggingen) )						$arr['ligging'] = concat_xml($object->Wonen->WonenDetails->MatenEnLigging->Liggingen->Ligging);
						if ( isset($object->Gebouw->GebouwDetails->Lokatie->Liggingen) )							$arr['ligging'] = concat_xml($object->Gebouw->GebouwDetails->Lokatie->Liggingen->Ligging);
						if ( isset($object->Gebouw->GebouwDetails->Lokatie->Parkeren) )								$arr['parkeerfaciliteiten'] = concat_xml($object->Gebouw->GebouwDetails->Lokatie->Parkeren->Parkeerfaciliteiten);
						if ( isset($object->Gebouw->Bedrijfsruimte->Kantoorruimte->Oppervlakte) )					$arr['oppervlakte_kantoor'] = (string)$object->Gebouw->Bedrijfsruimte->Kantoorruimte->Oppervlakte;

						if ( isset($object->Wonen->WonenDetails->SchuurBerging) )
						{
							$arr['schuur_berging'] = 1;
							$arr['schuur_berging_voorzieningen'] = concat_xml($object->Wonen->WonenDetails->SchuurBerging->Voorzieningen->Voorziening);
						}

						if ( isset($object->Wonen->WonenDetails->Garage) )
						{
							$arr['garage'] = 1;
							$arr['garage_voorzieningen'] = concat_xml($object->Wonen->WonenDetails->Garage->Soorten->Soort);
						}

						if ( isset($object->Wonen->WonenDetails->Tuin) )
						{
							$arr['tuin'] = 1;
							$arr['tuin_voorzieningen'] = concat_xml($object->Wonen->WonenDetails->Tuin->Tuintypen->Tuintype);
						}

						if ( isset($object->Wonen->WonenDetails->Diversen->Dak->Type) )								$arr['dak_type'] = (string)$object->Wonen->WonenDetails->Diversen->Dak->Type;
						if ( isset($object->Wonen->WonenDetails->Diversen->Dak->Materialen) )						$arr['dak_materialen'] = concat_xml($object->Wonen->WonenDetails->Diversen->Dak->Materialen->Materiaal);
						if ( isset($object->Wonen->WonenDetails->Diversen->Isolatievormen) )						$arr['isolatie'] = concat_xml($object->Wonen->WonenDetails->Diversen->Isolatievormen->Isolatie);
						if ( isset($object->Wonen->WonenDetails->Installatie->SoortenVerwarming) )					$arr['installatie_verwarming'] = concat_xml($object->Wonen->WonenDetails->Installatie->SoortenVerwarming->Verwarming);
						if ( isset($object->Wonen->WonenDetails->Installatie->SoortenWarmWater) )					$arr['installatie_warmwater'] = concat_xml($object->Wonen->WonenDetails->Installatie->SoortenWarmWater->WarmWater);
						if ( isset($object->Wonen->WonenDetails->VoorzieningenWonen) )								$arr['voorzieningen'] = concat_xml($object->Wonen->WonenDetails->VoorzieningenWonen->Voorziening);

						if ( isset($object->Wonen->Verdiepingen->Aantal) )											$arr['verdiepingen'] = (string)$object->Wonen->Verdiepingen->Aantal;
						if ( isset($object->Wonen->Verdiepingen->AantalKamers) )									$arr['kamers'] = (string)$object->Wonen->Verdiepingen->AantalKamers;
						if ( isset($object->Wonen->Verdiepingen->AantalSlaapKamers) )								$arr['slaapkamers'] = (string)$object->Wonen->Verdiepingen->AantalSlaapKamers;
						if ( isset($object->Gebouw->Bedrijfsruimte->Kantoorruimte->Verdiepingen) )					$arr['verdiepingen'] = (string)$object->Gebouw->Bedrijfsruimte->Kantoorruimte->Verdiepingen;

						// Nieuwbouw tussen de andere XML's
						//if ( isset($arr['bouwvorm']) && $arr['bouwvorm'] == 'nieuwbouw' )
						//	$arr['type'] = 2;

						// Toevoegen aan database array
						$objecten[] = $arr;
					}
				elseif ( isset($simpleXML->Project) )
					foreach ( $simpleXML->Project as $object )
					{
						// Nieuwbouwproject doorlopen en toevoegen aan database array
						$arr = array();

						$arr['object_code'] = (string)$object->ObjectCode;
						$arr['type'] = $type;
						$arr['koop_huur'] = '0';
						$arr['status'] = 'Nieuwbouw';
						$arr['prijstype'] = 'vrij op naam';

						if ( isset($object->ProjectDetails->Projectnaam) )											$arr['nieuwbouw_projectnaam'] = (string)$object->ProjectDetails->Projectnaam;
						if ( isset($object->ProjectDetails->OmschrijvingOpleveringVanaf) )							$arr['nieuwbouw_oplevering'] = (string)$object->ProjectDetails->OmschrijvingOpleveringVanaf;
						if ( isset($object->ProjectDetails->OmschrijvingStartBouw) )								$arr['bouwjaar'] = (string)$object->ProjectDetails->OmschrijvingOpleveringVanaf;

						if ( isset($object->MediaLijst) )
						{
							$arr['afbeeldingen'] = fotos((string)$object->ObjectCode, $object->MediaLijst);
							$arr['brochure'] = brochure((string)$object->ObjectCode, $object->MediaLijst);
							$arr['foto360'] = foto360($object->MediaLijst);
							$arr['plattegrond'] = plattegrond($object->MediaLijst);
						}

						if ( isset($object->ObjectDetails->Adres->Woonplaats) )
							$adres = $object->ObjectDetails->Adres;
						elseif ( isset($object->ObjectDetails->Adres->Nederlands) )
							$adres = $object->ObjectDetails->Adres->Nederlands;
						elseif ( isset($object->ProjectDetails->Adres->Woonplaats) )
							$adres = $object->ProjectDetails->Adres;
						elseif ( isset($object->ProjectDetails->Adres->Nederlands) )
							$adres = $object->ProjectDetails->Adres->Nederlands;
						else
							$adres = array();

						if ( isset($adres->Straatnaam) )															$arr['straat'] = (string)$adres->Straatnaam;
						if ( isset($adres->Huisnummer) )															$arr['huisnummer'] = (string)$adres->Huisnummer;
						if ( isset($adres->Huisnummer->Hoofdnummer) )												$arr['huisnummer'] = (string)$adres->Huisnummer->Hoofdnummer;
						if ( isset($adres->HuisnummerToevoeging) )													$arr['huisnummer_toevoeging'] = (string)$adres->HuisnummerToevoeging;
						if ( isset($adres->Postcode) )																$arr['postcode'] = (string)$adres->Postcode;
						if ( isset($adres->Woonplaats) )															$arr['plaats'] = (string)$adres->Woonplaats;
						if ( isset($adres->Land) )																	$arr['land'] = (string)$adres->Land;

						if ( isset($object->ProjectDetails->Maten->Inhoud->Van) )									$arr['inhoud'] = (string)$object->ProjectDetails->Maten->Inhoud->Van;
						if ( isset($object->ProjectDetails->Maten->Inhoud->TotEnMet) )								$arr['inhoud_tm'] = (string)$object->ProjectDetails->Maten->Inhoud->TotEnMet;
						if ( isset($object->ProjectDetails->Maten->Woonoppervlakte->Van) )							$arr['oppervlakte_wonen'] = (string)$object->ProjectDetails->Maten->Woonoppervlakte->Van;
						if ( isset($object->ProjectDetails->Maten->Woonoppervlakte->TotEnMet) )						$arr['oppervlakte_wonen_tm'] = (string)$object->ProjectDetails->Maten->Woonoppervlakte->TotEnMet;
						if ( isset($object->ProjectDetails->Maten->Perceeloppervlakte->Van) )						$arr['oppervlakte_perceel'] = (string)$object->ProjectDetails->Maten->Perceeloppervlakte->Van;
						if ( isset($object->ProjectDetails->Maten->Perceeloppervlakte->TotEnMet) )					$arr['oppervlakte_perceel_tm'] = (string)$object->ProjectDetails->Maten->Perceeloppervlakte->TotEnMet;

						if ( isset($object->ProjectDetails->Presentatie->Aanbiedingstekst) )						$arr['beschrijving_lang'] = (string)$object->ProjectDetails->Presentatie->Aanbiedingstekst;
						if ( isset($object->ProjectDetails->FinancieleGegevens->KoopAanneemsom->Van) )				$arr['prijs'] = (string)$object->ProjectDetails->FinancieleGegevens->KoopAanneemsom->Van;
						if ( isset($object->ProjectDetails->FinancieleGegevens->KoopAanneemsom->TotEnMet) )			$arr['prijs_tm'] = (string)$object->ProjectDetails->FinancieleGegevens->KoopAanneemsom->TotEnMet;

						// Toevoegen aan database array
						$objecten[] = $arr;

						// Controleren op Bouwtypes
						if ( isset($object->BouwType) )
							foreach ( $object->BouwType as $bouwtype )
							{
								// Bouwtype formatten voor database insert/update
								$bt = array
								(
									'object_code'	=> $arr['object_code'],
									'bouwtype_code'	=> (string)$bouwtype->ObjectCode
								);

								if ( isset($bouwtype->BouwTypeDetails->Naam) )											$bt['naam'] = (string)$bouwtype->BouwTypeDetails->Naam;
								if ( isset($bouwtype->BouwTypeDetails->DatumInvoer) )									$bt['ingevoerd'] = strtotime((string)$bouwtype->BouwTypeDetails->DatumInvoer);
								if ( isset($bouwtype->BouwTypeDetails->AantalEenheden) )								$bt['aantal_eenheden'] = (string)$bouwtype->BouwTypeDetails->AantalEenheden;
								if ( isset($bouwtype->BouwTypeDetails->AantalVrijeEenheden) )							$bt['vrije_eenheden'] = (string)$bouwtype->BouwTypeDetails->AantalVrijeEenheden;
								if ( isset($bouwtype->BouwTypeDetails->Aanbiedingstekst) )								$bt['beschrijving'] = (string)$bouwtype->BouwTypeDetails->Aanbiedingstekst;

								if ( isset($bouwtype->BouwTypeDetails->Maten->Inhoud->Van) )							$bt['inhoud_van'] = (string)$bouwtype->BouwTypeDetails->Maten->Inhoud->Van;
								if ( isset($bouwtype->BouwTypeDetails->Maten->Inhoud->TotEnMet) )						$bt['inhoud_tm'] = (string)$bouwtype->BouwTypeDetails->Maten->Inhoud->TotEnMet;
								if ( isset($bouwtype->BouwTypeDetails->Maten->Woonoppervlakte->Van) )					$bt['oppervlakte_wonen_van'] = (string)$bouwtype->BouwTypeDetails->Maten->Woonoppervlakte->Van;
								if ( isset($bouwtype->BouwTypeDetails->Maten->Woonoppervlakte->TotEnMet) )				$bt['oppervlakte_wonen_tm'] = (string)$bouwtype->BouwTypeDetails->Maten->Woonoppervlakte->TotEnMet;
								if ( isset($bouwtype->BouwTypeDetails->Maten->Perceeloppervlakte->Van) )				$bt['oppervlakte_perceel_van'] = (string)$bouwtype->BouwTypeDetails->Maten->Perceeloppervlakte->Van;
								if ( isset($bouwtype->BouwTypeDetails->Maten->Perceeloppervlakte->TotEnMet) )			$bt['oppervlakte_perceel_tm'] = (string)$bouwtype->BouwTypeDetails->Maten->Perceeloppervlakte->TotEnMet;

								if ( isset($bouwtype->BouwTypeDetails->FinancieleGegevens->KoopAanneemsom->Van) )		$bt['prijs_van'] = (string)$bouwtype->BouwTypeDetails->FinancieleGegevens->KoopAanneemsom->Van;
								if ( isset($bouwtype->BouwTypeDetails->FinancieleGegevens->KoopAanneemsom->TotEnMet) )	$bt['prijs_tm'] = (string)$bouwtype->BouwTypeDetails->FinancieleGegevens->KoopAanneemsom->TotEnMet;

								if ( isset($bouwtype->MediaLijst) )
								{
									$bt['afbeeldingen'] = fotos((string)$bouwtype->ObjectCode, $bouwtype->MediaLijst);
									$bt['brochure'] = brochure((string)$bouwtype->ObjectCode, $bouwtype->MediaLijst);
									$bt['foto360'] = foto360($bouwtype->MediaLijst);
									$bt['plattegrond'] = plattegrond($bouwtype->MediaLijst);
								}

								// Toevoegen aan bouwtypes-array
								$objecten_bouwtypes[] = $bt;

								// Controleren op Bouwnummers
								if ( isset($bouwtype->BouwNummer) )
									foreach ( $bouwtype->BouwNummer as $bouwnummer )
									{
										$btn = array
										(
											'object_code'		=> $arr['object_code'],
											'bouwtype_code'		=> $bt['bouwtype_code'],
											'bouwnummer_code'	=> (string)$bouwnummer->ObjectCode
										);

										if ( isset($bouwnummer->BouwNummerDetails->DatumInvoer) )														$btn['ingevoerd'] = strtotime((string)$bouwnummer->BouwNummerDetails->DatumInvoer);
										if ( isset($bouwnummer->BouwNummerDetails->Aanbiedingstekst) )													$btn['beschrijving'] = (string)$bouwnummer->BouwNummerDetails->Aanbiedingstekst;
										if ( isset($bouwnummer->BouwNummerDetails->Maten->Inhoud) )														$btn['inhoud'] = (string)$bouwnummer->BouwNummerDetails->Maten->Inhoud;
										if ( isset($bouwnummer->BouwNummerDetails->Maten->Woonoppervlakte) )											$btn['oppervlakte_wonen'] = (string)$bouwnummer->BouwNummerDetails->Maten->Woonoppervlakte;
										if ( isset($bouwnummer->BouwNummerDetails->Maten->Perceeloppervlakte) )											$btn['oppervlakte_perceel'] = (string)$bouwnummer->BouwNummerDetails->Maten->Perceeloppervlakte;

										if ( isset($bouwnummer->BouwNummerDetails->Wonen->Appartement->SoortAppartement) )								$btn['soort'] = (string)$bouwnummer->BouwNummerDetails->Wonen->Appartement->SoortAppartement;
										if ( isset($bouwnummer->BouwNummerDetails->Wonen->Woonhuis->SoortWoning) )										$btn['soort'] = (string)$bouwnummer->BouwNummerDetails->Wonen->Woonhuis->SoortWoning;
										if ( isset($bouwnummer->BouwNummerDetails->Bestemming->Hoofdbestemming) )										$btn['soort'] = (string)$bouwnummer->BouwNummerDetails->Bestemming->Hoofdbestemming;
										if ( isset($bouwnummer->BouwNummerDetails->Bouwvorm) )															$btn['bouwvorm'] = (string)$bouwnummer->BouwNummerDetails->Bouwvorm;
										if ( isset($bouwnummer->BouwNummerDetails->Status->ObjectStatus) )												$btn['status'] = (string)$bouwnummer->BouwNummerDetails->Status->ObjectStatus;
										if ( isset($bouwnummer->BouwNummerDetails->OppervlaktePerceel) )												$btn['oppervlakte_perceel'] = $bouwnummer->BouwNummerDetails->OppervlaktePerceel;
										if ( isset($bouwnummer->BouwNummerDetails->Wonen->Details->MatenEnLigging->Inhoud) )							$btn['inhoud'] = (string)$bouwnummer->BouwNummerDetails->Wonen->Details->MatenEnLigging->Inhoud;
										if ( isset($bouwnummer->BouwNummerDetails->Wonen->Details->MatenEnLigging->GebruiksoppervlakteWoonfunctie) )	$btn['oppervlakte_wonen'] = (string)$bouwnummer->BouwNummerDetails->Wonen->Details->MatenEnLigging->GebruiksoppervlakteWoonfunctie;
										if ( isset($bouwnummer->BouwNummerDetails->Wonen->Details->Bouwjaar->JaarOmschrijving->Jaar) )					$btn['bouwjaar'] = (string)$bouwnummer->BouwNummerDetails->Wonen->Details->Bouwjaar->JaarOmschrijving->Jaar;
										if ( isset($bouwnummer->BouwNummerDetails->Gebouw->GebouwDetails->Bouwjaar->Periode) )							$btn['bouwjaar'] = (string)$bouwnummer->BouwNummerDetails->Gebouw->GebouwDetails->Bouwjaar->Periode;
										if ( isset($bouwnummer->BouwNummerDetails->Wonen->Details->Onderhoud->Binnen->Waardering) )						$btn['onderhoud_binnen'] = (string)$bouwnummer->BouwNummerDetails->Wonen->Details->Onderhoud->Binnen->Waardering;
										if ( isset($bouwnummer->BouwNummerDetails->Wonen->Details->Onderhoud->Buiten->Waardering) )						$btn['onderhoud_buiten'] = (string)$bouwnummer->BouwNummerDetails->Wonen->Details->Onderhoud->Buiten->Waardering;

										if ( isset($bouwtype->MediaLijst) )
										{
											$btn['afbeeldingen'] = fotos((string)$bouwtype->ObjectCode, $bouwtype->MediaLijst);
											$btn['brochure'] = brochure((string)$bouwtype->ObjectCode, $bouwtype->MediaLijst);
											$btn['foto360'] = foto360($bouwtype->MediaLijst);
											$btn['plattegrond'] = plattegrond($bouwtype->MediaLijst);
										}

										if ( isset($bouwnummer->BouwNummerDetails->Financieel->Koop) )
										{
											$btn['koop_huur'] = 0;

											if ( isset($bouwnummer->BouwNummerDetails->Financieel->Koop->Koopprijs) )									$btn['prijs'] = (string)$bouwnummer->BouwNummerDetails->Financieel->Koop->Koopprijs;
											if ( isset($bouwnummer->BouwNummerDetails->Financieel->Koop->PrijsSpecificatie->Prijs) )					$btn['prijs'] = (string)$bouwnummer->BouwNummerDetails->Financieel->Koop->PrijsSpecificatie->Prijs;
											if ( isset($bouwnummer->BouwNummerDetails->Financieel->Koop->KoopConditie) )								$btn['prijstype'] = (string)$bouwnummer->BouwNummerDetails->Financieel->Koop->KoopConditie;
										}
										elseif ( $bouwnummer->BouwNummerDetails->Financieel->Huur )
										{
											$btn['koop_huur'] = 1;
											$btn['prijs'] = isset($bouwnummer->BouwNummerDetails->Financieel->Huur->Huurprijs) ? (string)$bouwnummer->BouwNummerDetails->Financieel->Huur->Huurprijs : (string)$bouwnummer->BouwNummerDetails->Financieel->Huur->PrijsSpecificatie->Prijs;
											$btn['prijstype'] = (string)$bouwnummer->BouwNummerDetails->Financieel->Huur->HuurConditie;
										}

										if ( ( isset($bouwnummer->BouwNummerDetails->Status->ObjectStatus) && !in_array((string)$bouwnummer->BouwNummerDetails->Status->ObjectStatus, array('Ingetrokken', 'Geveild', 'Verkocht', 'Verhuurd')) ) || ( isset($bouwnummer->BouwNummerDetails->Status->ObjectStatus) && !in_array((string)$bouwnummer->BouwNummerDetails->Status->ObjectStatus, array('Ingetrokken', 'Geveild', 'Verkocht', 'Verhuurd')) ) )
											$btn['verwijderd'] = '0';
										else
											$btn['verwijderd'] = '1';

										if ( isset($bouwnummer->BouwNummerDetails->Adres->Straatnaam) )
											$adres = $bouwnummer->BouwNummerDetails->Adres;
										elseif ( isset($bouwnummer->BouwNummerDetails->Adres->Nederlands) )
											$adres = $bouwnummer->BouwNummerDetails->Adres->Nederlands;
										else
											$adres = array();

										if ( isset($adres->Straatnaam) )															$btn['straat'] = (string)$adres->Straatnaam;
										if ( isset($adres->Huisnummer) )															$btn['huisnummer'] = (string)$adres->Huisnummer;
										if ( isset($adres->Huisnummer->Hoofdnummer) )												$btn['huisnummer'] = (string)$adres->Huisnummer->Hoofdnummer;
										if ( isset($adres->HuisnummerToevoeging) )													$btn['huisnummer_toevoeging'] = (string)$adres->HuisnummerToevoeging;
										if ( isset($adres->Postcode) )																$btn['postcode'] = (string)$adres->Postcode;
										if ( isset($adres->Woonplaats) )															$btn['plaats'] = (string)$adres->Woonplaats;
										if ( isset($adres->Land) )																	$btn['land'] = (string)$adres->Land;

										if ( isset($bouwnummer->BouwNummerDetails->Wonen->Details->MatenEnLigging->Liggingen) )				$btn['ligging'] = concat_xml($bouwnummer->BouwNummerDetails->Wonen->Details->MatenEnLigging->Liggingen->Ligging);
										if ( isset($bouwnummer->BouwNummerDetails->Gebouw->GebouwDetails->Lokatie->Liggingen) )				$btn['ligging'] = concat_xml($bouwnummer->BouwNummerDetails->Gebouw->GebouwDetails->Lokatie->Liggingen->Ligging);
										if ( isset($bouwnummer->BouwNummerDetails->Gebouw->GebouwDetails->Lokatie->Parkeren) )				$btn['parkeerfaciliteiten'] = concat_xml($bouwnummer->BouwNummerDetails->Gebouw->GebouwDetails->Lokatie->Parkeren->Parkeerfaciliteiten);
										if ( isset($bouwnummer->BouwNummerDetails->Gebouw->Bedrijfsruimte->Kantoorruimte->Oppervlakte) )	$btn['oppervlakte_kantoor'] = (string)$bouwnummer->BouwNummerDetails->Gebouw->Bedrijfsruimte->Kantoorruimte->Oppervlakte;

										if ( isset($bouwnummer->BouwNummerDetails->Wonen->Details->SchuurBerging) )
										{
											$btn['schuur_berging'] = 1;
											$btn['schuur_berging_voorzieningen'] = concat_xml($bouwnummer->BouwNummerDetails->Wonen->Details->SchuurBerging->Voorzieningen->Voorziening);
										}

										if ( isset($bouwnummer->BouwNummerDetails->Wonen->Details->Garage) )
										{
											$btn['garage'] = 1;
											$btn['garage_voorzieningen'] = concat_xml($bouwnummer->BouwNummerDetails->Wonen->Details->Garage->Soorten->Soort);
										}

										if ( isset($bouwnummer->BouwNummerDetails->Wonen->Details->Tuin) )
										{
											$btn['tuin'] = 1;
											$btn['tuin_voorzieningen'] = concat_xml($bouwnummer->BouwNummerDetails->Wonen->Details->Tuin->Tuintypen->Tuintype);
										}

										if ( isset($bouwnummer->BouwNummerDetails->Wonen->Details->Diversen->Dak->Type) )								$btn['dak_type'] = (string)$bouwnummer->BouwNummerDetails->Wonen->Details->Diversen->Dak->Type;
										if ( isset($bouwnummer->BouwNummerDetails->Wonen->Details->Diversen->Dak->Materialen) )						$btn['dak_materialen'] = concat_xml($bouwnummer->BouwNummerDetails->Wonen->Details->Diversen->Dak->Materialen->Materiaal);
										if ( isset($bouwnummer->BouwNummerDetails->Wonen->Details->Diversen->Isolatievormen) )						$btn['isolatie'] = concat_xml($bouwnummer->BouwNummerDetails->Wonen->Details->Diversen->Isolatievormen->Isolatie);
										if ( isset($bouwnummer->BouwNummerDetails->Wonen->Details->Installatie->SoortenVerwarming) )					$btn['installatie_verwarming'] = concat_xml($bouwnummer->BouwNummerDetails->Wonen->Details->Installatie->SoortenVerwarming->Verwarming);
										if ( isset($bouwnummer->BouwNummerDetails->Wonen->Details->Installatie->SoortenWarmWater) )					$btn['installatie_warmwater'] = concat_xml($bouwnummer->BouwNummerDetails->Wonen->Details->Installatie->SoortenWarmWater->WarmWater);
										if ( isset($bouwnummer->BouwNummerDetails->Wonen->Details->VoorzieningenWonen) )								$btn['voorzieningen'] = concat_xml($bouwnummer->BouwNummerDetails->Wonen->Details->VoorzieningenWonen->Voorziening);

										if ( isset($bouwnummer->BouwNummerDetails->Wonen->Verdiepingen->Aantal) )											$btn['verdiepingen'] = (string)$bouwnummer->BouwNummerDetails->Wonen->Verdiepingen->Aantal;
										if ( isset($bouwnummer->BouwNummerDetails->Wonen->Verdiepingen->AantalKamers) )									$btn['kamers'] = (string)$bouwnummer->BouwNummerDetails->Wonen->Verdiepingen->AantalKamers;
										if ( isset($bouwnummer->BouwNummerDetails->Wonen->Verdiepingen->AantalSlaapKamers) )								$btn['slaapkamers'] = (string)$bouwnummer->BouwNummerDetails->Wonen->Verdiepingen->AantalSlaapKamers;
										if ( isset($bouwnummer->BouwNummerDetails->Gebouw->Bedrijfsruimte->Kantoorruimte->Verdiepingen) )					$btn['verdiepingen'] = (string)$bouwnummer->BouwNummerDetails->Gebouw->Bedrijfsruimte->Kantoorruimte->Verdiepingen;

										$objecten_bouwtypes_bouwnummers[] = $btn;
									}
							}
					}
				else
					$errors[] = 'Geen correct type gevonden (Object of Project)';
			}

		// Directory legen
		foreach ( scandir($dir) as $f ) {
            if (strtolower(substr($f, -4)) == '.xml') {
                rename($dir . '/' . $f, TMP . '/' . $f);
			} elseif ( !in_array($f, array('.', '..')) ) {
				unlink($dir . $f);
            }
        }
	}

	// Temp directory verwijderen
	rmdir($dir);

	// Objecten verwerken
	if ( count($objecten) > 0 )
	{
		$id_list = array('0');

		foreach ( $objecten as $object )
		{
			$nullables = array('oppervlakte_perceel', 'oppervlakte_perceel_tm', 'oppervlakte_wonen', 'oppervlakte_wonen_tm', 'oppervlakte_kantoor');
			foreach ($nullables as $nullable) {
				if (!isset($object[$nullable])) {
					$object[$nullable] = 0;
				}
			}

			if ( $object_model->findCount(array('object_code' => $object['object_code'])) > 0 )
			{
				$object['id'] = $object_model->get('id', array('object_code' => $object['object_code']));
				$object_model->update($object);

				if ( !isset($object['verwijderd']) || $object['verwijderd'] == '0' )
					$id_list[] = $object['id'];
			}
			else
			{
				$object_model->insert($object);
				$object__id = $object_model->glid();
				$id_list[] = $object__id;
				$url = '/realworks/object/' . $object__id;
				$_route = str_replace(' ', '-', '/makelaardij/' . ( $object['type'] == 1 ? 'bedrijfsonroerendgoed' : ( $object['type'] == 2 ? 'nieuwbouw' : 'koopwoningen' ) ) . '/' . strtolower($object['soort'] . '-' . $object['straat'] . '-' . $object['huisnummer'] . '-' . $object['huisnummer_toevoeging'] . '-' . $object['plaats']));

				if ( isset($object['nieuwbouw_projectnaam']) )
					$_route .= '-' . str_replace(' ', '-', strtolower($object['nieuwbouw_projectnaam']));

				$route = '';
				for ( $i = 0; $i < strlen($_route); $i++ )
					if (  ( ord($_route[$i]) >= 97 && ord($_route[$i]) <= 122 ) || is_numeric($_route[$i]) || in_array($_route[$i], array('-', '/')) )
						$route .= $_route[$i];

				$route = str_replace('/-', '/', str_replace('--', '-', $route));
				if ( $route[strlen($route) - 1] == '-' )
					$route = substr($route, 0, -1);

				if ( $route_model->findCount(array('route' => $route)) > 0 || in_array($route, array('/makelaardij/nieuwbouw/', '/makelaardij/bedrijfsonroerendgoed/', '/makelaardij/koopwoningen/', '/makelaardij/nieuwbouw', '/makelaardij/bedrijfsonroerendgoed', '/makelaardij/koopwoningen')) )
					$route .= str_replace(' ', '-', $object['object_code']);

				$route = str_replace('--', '-', str_replace('/-', '/', $route));
				if ( $route[strlen($route) - 1] == '-' )
					$route = substr($route, -1);

				if ( $route_model->findCount(array('url' => $url)) == 0 )
					$route_model->insert
					(
						array
						(
							'url' => $url,
							'route' => $route,
							'language' => 1,
						)
					);
			}
		}

		// Objecten soft-verwijderen indien niet meer actief
		$object_model->query("UPDATE `objecten` SET `verwijderd` = 0");
		$object_model->query("UPDATE `objecten` SET `verwijderd` = 1 WHERE `id` NOT IN(" . implode(',', $id_list) . ") OR `afbeeldingen` = 'YTowOnt9'");
	}

	// Bouwtypes inserten/updaten
	$id_list_bt = array('0');

	if ( count($objecten_bouwtypes) > 0 )
	{
		foreach ( $objecten_bouwtypes as $objecten_bouwtype )
		{
			$objecten_bouwtype['object_id'] = $object_model->get('id', array('object_code' => $objecten_bouwtype['object_code']));

			if ( $object_bt_model->findCount(array('bouwtype_code' => $objecten_bouwtype['bouwtype_code'])) == 0 )
			{
				$object_bt_model->insert($objecten_bouwtype);
				$objecten_bouwtype['id'] = $object_bt_model->glid();
			}
			else
			{
				$objecten_bouwtype['id'] = $object_bt_model->get('id', array('bouwtype_code' => $objecten_bouwtype['bouwtype_code']));
				$object_bt_model->update($objecten_bouwtype);
			}

			$id_list_bt[] = $objecten_bouwtype['id'];

			// Route
			$url = '/realworks/bouwtype/' . $objecten_bouwtype['id'];
			if ( $route_model->findCount(array('url' => $url)) == 0 )
			{
				$_route = $route_model->getRoute('/realworks/object/' . $objecten_bouwtype['object_id']);
				$_route = $_route['Route']['route'];

				if ( $_route[strlen($_route) - 1] != '/' )
					$_route .= '/';

				$_route .= str_replace(' ', '-', strtolower($objecten_bouwtype['naam']));

				$route = '';
				for ( $i = 0; $i < strlen($_route); $i++ )
					if (  ( ord($_route[$i]) >= 97 && ord($_route[$i]) <= 122 ) || is_numeric($_route[$i]) || in_array($_route[$i], array('-', '/')) )
						$route .= $_route[$i];

				$route = str_replace('/-', '/', str_replace('--', '-', $route));
				if ( $route[strlen($route) - 1] == '-' )
					$route = substr($route, 0, -1);

				$route = str_replace('--', '-', str_replace('/-', '/', $route));
				if ( $route[strlen($route) - 1] == '-' )
					$route = substr($route, -1);

				if ( $route_model->findCount(array('url' => $url)) == 0 )
					$route_model->insert
					(
						array
						(
							'url' => $url,
							'route' => $route,
							'language' => 1,
						)
					);
			}
		}

		// Update
		$object_model->query("UPDATE `objecten_bouwtypes` SET `verwijderd` = 1 WHERE `id` NOT IN(" . implode(',', $id_list_bt) . ") OR `afbeeldingen` = 'YTowOnt9'");
	}

	// Bouwnummers inserten/updaten
	$id_list_bn = array('0');

	if ( count($objecten_bouwtypes_bouwnummers) > 0 )
		foreach ( $objecten_bouwtypes_bouwnummers as $objecten_bouwnr )
		{
			$objecten_bouwnr['object_id'] = $object_model->get('id', array('object_code' => $objecten_bouwnr['object_code']));
			$objecten_bouwnr['bouwtype_id'] = $object_bt_model->get('id', array('bouwtype_code' => $objecten_bouwnr['bouwtype_code']));

			if ( $object_bt_bn_model->findCount(array('bouwnummer_code' => $objecten_bouwnr['bouwnummer_code'])) == 0 )
			{
				$object_bt_bn_model->insert($objecten_bouwnr);
				$objecten_bouwnr['id'] = $object_bt_bn_model->glid();
			}
			else
			{
				$objecten_bouwnr['id'] = $object_bt_bn_model->get('id', array('bouwnummer_code' => $objecten_bouwnr['bouwnummer_code']));
				$object_bt_bn_model->update($objecten_bouwnr);
			}

			$id_list_bn[] = $objecten_bouwnr['id'];

			// Route
			$url = '/realworks/bouwnummer/' . $objecten_bouwnr['id'];
			if ( $route_model->findCount(array('url' => $url)) == 0 )
			{
				$_route = $route_model->getRoute('/realworks/bouwtype/' . $objecten_bouwnr['bouwtype_id']);
				$_route = $_route['Route']['route'];

				if ( $_route[strlen($_route) - 1] != '/' )
					$_route .= '/';

				$_route .= str_replace(' ', '-', strtolower($objecten_bouwnr['bouwnummer_code']));

				$route = '';
				for ( $i = 0; $i < strlen($_route); $i++ )
					if (  ( ord($_route[$i]) >= 97 && ord($_route[$i]) <= 122 ) || is_numeric($_route[$i]) || in_array($_route[$i], array('-', '/')) )
						$route .= $_route[$i];

				$route = str_replace('/-', '/', str_replace('--', '-', $route));
				if ( $route[strlen($route) - 1] == '-' )
					$route = substr($route, 0, -1);

				$route = str_replace('--', '-', str_replace('/-', '/', $route));
				if ( $route[strlen($route) - 1] == '-' )
					$route = substr($route, -1);

				if ( $route_model->findCount(array('url' => $url)) == 0 )
					$route_model->insert
					(
						array
						(
							'url' => $url,
							'route' => $route,
							'language' => 1,
						)
					);
			}
		}

	// Objecten soft-verwijderen indien niet meer actief
	$object_model->query("UPDATE `objecten_bouwtypes_bouwnummers` SET `verwijderd` = 1 WHERE `id` NOT IN(" . implode(',', $id_list_bn) . ") OR `afbeeldingen` = 'YTowOnt9'");

	// Output
	if ( count($errors) == 0 )
		echo "\r\nCronjob succesvol uitgevoerd, " . count($objecten) . " objecten verwerkt.\r\n";
	else
	{
		echo "\r\nCronjob uitgevoerd met fouten:\r\n";
		debug($errors);
		echo "\r\n";
	}
?>
