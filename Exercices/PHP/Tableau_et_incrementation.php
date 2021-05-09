<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercices</title>
</head>
<body>
    <h1>Exercice</h1>
    <p>Tableaux et incrémentation.</p>

    <h2>Contexte : </h2>
    <p>
        Vous possédez un tableau $array = [1,2,3] 
        Vous devez incrémenter la dernière valeur du tableau afin d'obtenir le résultat [1,2,4]
        Pour un tableau [9,9], le résultat devra être : [1,0,0]    
    </p>


    <h3>Solution : 
    <p>
        La solution la plus simple revient à transformer le tableau en nombre (123), puis l'incrémenter
        Ensuite, on transforme le resultat obtenu en tableau et on retourne la valeur
        <hr>
      
        <?php
            function increment(array $array) {
                $array = implode($array);  
                $array = intval($array);    
                $array++;                   
                $array = str_split($array); 

                return $array;
            }

            var_dump(increment([1,2,3])); // returns : [1,2,4]
            var_dump(increment([9,9])); // returns : [1,0,0]
        ?>

    </p>

</body>
</html>
