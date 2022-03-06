# DeenneDebugger
DéenneDébugger est une class PHP vous permettant de simplifier le débuggage de vos sites en permettant un affichage de variables, l'ajout de logs ainsi qu 'un calcul du temps de traitement de votre script.
-----

## Usage :

### Initialisation:
Pour initialiser la class vous devez ajouter un *require* au début de votre fichier et créer une instance.
~~~
// Chemin à adapter selon votre structure de fichiers
require "./DeenneDebugger.php";

$dd = new DeenneDebugger();
~~~

### Affichage d'une variable:
L'affichage de variables est inspiré de Symfony, pour cela nous avons deux fonctions.
~~~
// Affichage de la variable
$dd->dump($variableAAfficher);

// Affichage de la variable & arrêt du script
$dd->dd($variableAAfficher);
~~~

Il est également possible d'ajouter un style prédéfini à l'affichage en l'ajoutant à la fonction.
~~~
/*
* Styles disponibles:
* INFO (fond bleu)
* VALID (fond vert)
* ALERT (fond jaune)
* ERROR (fond rouge)
*/

$dd->dump($variableAAfficher, "INFO");
$dd->dd($variableAAfficher, "ERROR");
~~~
