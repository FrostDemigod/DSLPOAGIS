# DSLPOA GIS

## Steps for Exporting Map from QGIS using qgis2web

Follow these steps to export your GIS map from QGIS using the qgis2web plugin:

### **1. Prepare Your Map in QGIS**
- Open QGIS and load your project.
- Ensure all layers are correctly styled and labeled.
- Verify that the Coordinate Reference System (CRS) is set correctly (preferably **WGS 84 / EPSG:4326** for web maps).
- Save your project before proceeding.

### **2. Install qgis2web Plugin (If Not Installed)**
- Go to **Plugins** > **Manage and Install Plugins**.
- Search for **qgis2web**.
- Click **Install** (or **Upgrade** if an update is available).

### **3. Export the Map**
1. Navigate to **Web** > **qgis2web** > **Create Web Map**.
2. In the qgis2web dialog box:
   - Select the **Leaflet** export format.
   - Ensure that the correct layers are included.
   - Adjust export settings such as zoom level, popups, and layer visibility.
   - While adjusting the **Appearance Settings:**
     - **Show popups on hover:** Uncheck this option because we are using a **click action** to display information about a given point rather than hover-based popups.
     - **Restrict to extent:** Uncheck this option as we are using the **lake bounds layer** instead to define the visible area, allowing flexibility in viewing the map.
3. Click **Export** and choose a folder to save the exported files.

### **4. Locate the Exported Files**
- The exported web map files will be saved in the selected directory.
- Inside the folder, you will find:
  - `index.html` (Main webpage)
  - `data/` (GeoJSON data files)
  - `css/` (Styling files)
  - `js/` (JavaScript files for map functionality)

### **5. Open the Map in a Browser**
- Navigate to the export folder.
- Double-click on `index.html` to open the map in a web browser.

### **6. Troubleshooting**
- If the map does not display correctly:
  - Ensure all layer data files are correctly generated in the `data/` folder.
  - Check for errors in the QGIS Python console (`Ctrl + Alt + P`).
  - Verify that your layers are not missing CRS or required attributes.
  - Try exporting again with different settings.

### **7. Deploying the Map Online**
- Upload the entire exported folder to a web server.
- Use services like **GitHub Pages** or an FTP server to host your map.

### **8. Additional Resources**
- [QGIS2Web Documentation](https://qgis.org)
- [QGIS Official Documentation](https://docs.qgis.org)

---

This guide ensures a smooth export process for sharing GIS maps online. For any issues, consult the QGIS logs or community forums.


### To Remove layer checkbox from the map: 
Look and comment out the following in index.html:

```html
    var lay = L.control.layers.tree(null, overlaysTree,{
        ...
        collapsed: false, 
    });
    lay.addTo(map);
```

And add:

```html

        // Map layers
        const layers = {
            BirdWatchingPoints: layer_bird_watching_points_13,
            BeaverDams: layer_beaver_dam_12,
            KayakingRoutes: layer_kayak_route_11,
            Hazards: layer_Hazards_10,
            InvasiveSpecies: layer_invasive_species_9,
        };

        // Listen for messages from the parent page to toggle layers
        window.addEventListener('message', (event) => {
            const { action, layerName } = event.data;

            if (action === 'toggleLayer' && layers[layerName]) {
                const layer = layers[layerName];
                if (map.hasLayer(layer)) {
                    map.removeLayer(layer);
                } else {
                    map.addLayer(layer);
                }
            }
        });
```