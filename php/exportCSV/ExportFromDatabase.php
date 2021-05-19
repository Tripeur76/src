<?php
    // EXPORT CSV
        header('Content-Type: text/csv;');
        header('Content-Disposition: attachment; filename="export.csv"');

        // Connexion PDO
        try{
            $pdo = new PDO('mysql:host=localhost;dbname=dev','root','');
            $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_WARNING);
            $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE,PDO::FETCH_OBJ);
        }
        catch(PDOException $e) {
            throw new Exception('Connexion SQL impossible');
        }
        
        $keys = '*';
        $table = 'task';
        $params = 'ORDER BY id DESC';

        // Extract SQL data
        $req = 'SELECT '.$keys.' FROM '.$table.' '.$params.' ';
        $req = $pdo->prepare($req);
        $req->execute();
        $data = $req->fetchAll();

        echo '"ID";"Etat";"Titre"';
        foreach($data as $d)
        {   
            echo "\n".'"'.$d->id.'";"'.$d->etat.'";"'.$d->title.'"';
        }
        
        
        /*
        // data
        $client = [
            'nom' => 'username',
            'prenom' => 'userfisrtname',
            'tel' => '0123456789'
        ];


        // output : "id";"nom";"prenom";....


        echo '"nom";"prenom";"tel"';
        echo "\n".'"'.$client['nom'].'";"'.$client['prenom'].'";"'.$client['tel'].'"';
        */
?>

