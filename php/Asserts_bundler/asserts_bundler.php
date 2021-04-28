<style>
    body {font-family:sans-serif;line-height:2rem;}
    table {width:100%;border-collapse:collapse;margin-bottom:2rem;}
    thead{border-bottom:1px solid silver;}
    th {width:25%;text-align:left;}
    th:first-child{width:50%;}
    td{padding:.5rem;}
    .success {background:#d1e7dd;}
</style>
<h1>Assets Bundler</h1>

<?php
// Fonction : centraliser les assets en un seul fichier 

init('./build/');

build('./assets/');
build('./assets/js/');


function init($dir) {
    // suppression de app.css et app.js
    $files = ['app.css', 'app.js'];
    foreach($files as $file) {
        $path = $dir . $file;
        if(is_file($path)) {unlink($path);}
    }
}

// récupère la liste des fichiers et les stock dans un tableau $assets
function build(string $dir) {    

    echo '<strong>Traitement des fichiers contenus dans '.$dir.'</strong>';

    echo '<table>';
    echo '<thead>
            <tr>
                <th>Source</th>
                <th>Destination</th>
                <th>Etat</th>
            </tr>
        </thead>';

    // boucle sur chaque élément du dossier
    foreach(scandir($dir) as $object) {

        $path = $dir . $object;

        // Ajout du contenu du fichier

        if(is_file($path)) {  


            $extension = explode('.',$path);
            $extension = end($extension);

            $output = './build/app.'.$extension;
            file_put_contents($output, file_get_contents($path), FILE_APPEND); 
            
            
            echo '
            <tr>
                <td>'.$path.'</td>
                <td>'.$output.'</td>
                <td class="success">Terminé</td>
            </tr>';
        }        
    }
    echo '</table>';
}

?>

