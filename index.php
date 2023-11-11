<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>index</title>
	
<style>
    html,
    body,
    #viewDiv {
      padding: 0;
      margin: 0;
      height: 100%;
      width: 100%;
    }
	  #viewDiv{
		  background: #CCC;
	  }
</style>

<link rel="stylesheet" href="https://js.arcgis.com/calcite-components/1.10.0/calcite.css" />
<script src="https://js.arcgis.com/calcite-components/1.10.0/calcite.esm.js" type="module"></script>
	
<link rel="stylesheet" href="https://js.arcgis.com/4.27/esri/themes/light/main.css" />
	
<script type="module">
  	import esriConfig from "https://js.arcgis.com/4.27/@arcgis/core/config.js";
	import Map from "https://js.arcgis.com/4.27/@arcgis/core/Map.js";
	import MapView from 'https://js.arcgis.com/4.27/@arcgis/core/views/MapView.js';
	import TileLayer from "https://js.arcgis.com/4.27/@arcgis/core/layers/TileLayer.js";
	import FeatureLayer from "https://js.arcgis.com/4.27/@arcgis/core/layers/FeatureLayer.js";
	import Expand from "https://js.arcgis.com/4.27/@arcgis/core/widgets/Expand.js";
	import Legend from "https://js.arcgis.com/4.27/@arcgis/core/widgets/Legend.js";
	
	/*
	* Configuration
	*/
	const nameSpace = 'tpgrf';
	
	esriConfig.apiKey = "AAPKc01264321345470f95a25c2985389004vgahiu9QG_9h93o0jzCzL9L_K372hh6jHVCT2r6XbBrELyVKyqvmeQCnPk5PWeLC";
	
	const basemapLayer = new TileLayer({
		url: "https://tiles.arcgis.com/tiles/nSZVuSZjHpEZZbRo/arcgis/rest/services/Topografie_in_de_klas_nederland_ondergrond/MapServer"
	});
	const contentLayer = new FeatureLayer({
		url: "https://services.arcgis.com/nSZVuSZjHpEZZbRo/ArcGIS/rest/services/Topografie_in_de_klas_nederland/FeatureServer/0"
	});
	const highlightLayer = new FeatureLayer({
		url: "https://services.arcgis.com/nSZVuSZjHpEZZbRo/ArcGIS/rest/services/Topografie_in_de_klas_nederland/FeatureServer/1"
	});
	
	/*
	* Build map and view
	*/
	const map = new Map({
		basemap: "arcgis-topographic", // Basemap 
		layers: [
			basemapLayer, 
			contentLayer, 
			//highlightLayer
		],
    });
	
	const view = new MapView({
        map: map,
		extent: {
			xmin: -84997,
			ymin: 6397472,
			xmax: 1342234,
			ymax: 7167957,
			spatialReference: 102100
		},
        zoom: 8, // Zoom level
        container: "viewDiv", // Div element
		highlightOptions: {
            color: [255, 0, 0],
            fillOpacity: 0.8
        }
    });
	
	//When the view is ready, do things add a legend, that is expandable
	view.when(() => {
		
		//Construct an expandable legend
		const legend = new Legend({
			view: view,
			layerInfos: [
				{
					layer: contentLayer,
					title: "Legenda"
				}
			]
		});
		const legendExpand = new Expand({
			expandIcon: "legend",
			expanded: true,
			view: view,
			content: legend
		});
		
		//Construct sidebarRight
		const sidebareRight = document.getElementById("sidebarRight");
		
		//Add it to the UI
		view.ui.add(legendExpand, "top-left");
		//view.ui.move("zoom", "top-left");
		view.ui.add(sidebareRight, "top-right");
	});
	
	//When the contentLayer is ready, let's highlight a single Feature
	view.whenLayerView(contentLayer).then((layerView) => {
		// these two highlight handlers are used for selection and hovering over features
		let highlightSelect;

		// these two highlight handlers are used for selection and hovering over features
		const queryContent = contentLayer.createQuery();
		queryContent.where = `Cito100_onderdeel=1`;
		contentLayer.queryFeatures(queryContent).then((result) => {
			// if a feature is already highlighted, then remove the highlight
			if (highlightSelect) {
				highlightSelect.remove();
			}

			// the feature to be highlighted
			const random = Math.floor(Math.random() * (99 - 1) + 1);
			console.log("random="+random);
			const feature = result.features[random];

			// use the objectID to highlight the feature
			highlightSelect = layerView.highlight(feature.attributes["OBJECTID"]);
		});
	 });
	
	/*
	 * HowItWorks modal
	*/
	const ls_howitworks = localStorage.getItem(nameSpace + '_hide_howItWorks');
	if(ls_howitworks == true){
		
	}
	
	/*
	 * Handeling User Interaction
	*/
	//const button = document.getElementById("trainer-settings-toggle-button");
    const modalTrainerSettings = document.getElementById("modalTrainerSettings");
	const modalHowItWorks = document.getElementById("modalHowItWorks");

    document.querySelectorAll('.modalTrainerSettings-toggle-button').forEach(item => {
	  item.addEventListener('click', event => {
		  modalTrainerSettings.open = !modalTrainerSettings.open;
	  })
	});
	document.querySelectorAll('.modalHowItWorks-toggle-button').forEach(item => {
	  item.addEventListener('click', event => {
		  modalHowItWorks.open = !modalHowItWorks.open;
	  })
	});
	document.getElementById('.modalHowItWorks-submit-button').addEventListener("click", event => {
		console.log(".modalHowItWorks-sumit-button clicked");
		
		//const hideHowItWorksValue = 
	});
	

</script>

</head>

<body>
	<h1>Test</h1>
	
	<div id="viewDiv"></div>
	
	<div id="sidebarRight" class="esri-widget">

		<calcite-shell-panel slot="panel-end" display-mode="float" width-scale="m">
          
			<calcite-panel heading="Topotrainer">
				<calcite-action slot="header-actions-end" icon="information" text="Uitleg" class="modalHowItWorks-toggle-button"></calcite-action>
                <calcite-action slot="header-actions-end" icon="gear" text="Instellingen" class="modalTrainerSettings-toggle-button" ></calcite-action>
				
				<calcite-block heading="Welke XX is aangewezen op de kaart?" open="true">
					<calcite-label>
						<calcite-input-text placeholder="schrijf hier jouw antwoord"></calcite-input-text>
					</calcite-label>
					<calcite-label>
						<calcite-button name="trainerSubmitAnswer" width="full" >
						Controleer
						</calcite-button>
					</calcite-label>
					<br/>
					<calcite-label scale="s" layout="inline">
						Of druk op enter om jouw antwoord te controleren
						<calcite-button name="iGiveUp" width="auto" kind="neutral">
						Ik weet het niet
						</calcite-button>
					</calcite-label>
					<br/><br/>
					<calcite-label scale="s" name="progressText" layout="inline">
						Je hebt <calcite-chip value="0" name="tasksDone" scale="s">0</calcite-chip> van de <calcite-chip value="100" name="tasksTotal" scale="s">100</calcite-chip> vragen beantwoord
					</calcite-label>
					<calcite-label scale="l">
						<!--@TODO: overweeg https://developers.arcgis.com/calcite-design-system/components/meter/  -->
						<calcite-progress value="0.8" ></calcite-progress>
					</calcite-label>
					<calcite-label scale="s" name="" layout="inline">
						Je had <calcite-chip value="0 goed" name="tasksRight" scale="s" >0 goed</calcite-chip> en <calcite-chip value="0 fout" name="tasksWrong" scale="s">0 fout</calcite-chip>
					</calcite-label>
					<br/>
				</calcite-block>
			</calcite-panel>
          
        </calcite-shell-panel>
	</div>
	
	
	<calcite-modal scale="s" width="s" id="modalTrainerSettings">
		<div slot="header" id="modal-title">
            Instellingen
        </div>
        <div slot="content">
            <calcite-label>
            	Inhoud
                <calcite-segmented-control scale="l" width="full">
                	<calcite-segmented-control-item icon-start="pin" value="cito100" name="content" value="Cito100" checked>
                            Cito 100
                    </calcite-segmented-control-item>
                    <calcite-segmented-control-item icon-start="pin-plus" value="citoplus" name="content" value="CitoPlus">
                            Cito Plus
                    </calcite-segmented-control-item>
                </calcite-segmented-control>
        	</calcite-label>

           	<calcite-label>
                Types
            	<calcite-tile-select-group layout="horizontal">
                	<calcite-tile-select checked input-enabled input-alignment="start" type="checkbox" heading="Plaatsen" name="type" value="Plaats"></calcite-tile-select>
                    <calcite-tile-select checked input-enabled input-alignment="start" type="checkbox" heading="Provincies" name="type" value="Provincie"></calcite-tile-select>
                    <calcite-tile-select checked input-enabled input-alignment="start" type="checkbox" heading="Gebieden" name="type" value="Gebied"></calcite-tile-select>
                    <calcite-tile-select checked input-enabled input-alignment="start" type="checkbox" heading="Wateren" name="type" value="Water"></calcite-tile-select>
				</calcite-tile-select-group>
            </calcite-label>

            <calcite-label>
            	<calcite-button name="buttonStartTest" width="full" icon-end="play-f" class="modalTrainerSettings-toggle-button" >
                    Start
                </calcite-button>
            </calcite-label>
        </div>
		</calcite-modal>
	
		<calcite-modal scale="s" width="s" id="modalHowItWorks">
		<div slot="header" id="modal-title">
            Hoe het werkt
        </div>
        <div slot="content">
            <calcite-label>
            	Spelling
                @TODO: uitleg
        	</calcite-label>

           	<calcite-label>
                Zoomen
				@TODO
            </calcite-label>
			
            <calcite-label>
            	<calcite-button width="full" class="modalHowItWorks-toggle-button modalHowItWorks-submit-button" >
                    Ga door
                </calcite-button>
            </calcite-label>
			<calcite-label>
				<calcite-checkbox name="howItWorks-hide" guid="howItWorks-hide" checked="false"></calcite-checkbox> Toon niet meer op dit apparaat
			</calcite-label>
			
        </div>
		</calcite-modal>
	
</body>
</html>
