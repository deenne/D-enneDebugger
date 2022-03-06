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
/*
* AFFICHAGE DE LA VARIABLE
* $variableAAfficher correspond à la variable à afficher
* (string) $style correspondant au style d'affichage de la variable. 
* (bool) $return 
*/
$dd->dump($variableAAfficher, $style, $return);

// Affichage de la variable & arrêt du script
$dd->dd($variableAAfficher, $style);
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

### Calcul du temps de traitement :
Le calcul du temps de traitement nécéssite deux fonctions, la première doit être exécutée avant ce dont vous souhaitez obtenir le temps de traitement et la seconde après.
~~~
// Début du script / de la fonction:
$dd->startLoad();

// Script ou fonction dont on cherche à obtenir le temps de traitement

// Fin du script / de la fonction:
/*
*
*/

$tempsTraitement = $dd->endLoad();
~~~

### Barre de débuggage
La barre de débuggage vous permet d'afficher des logs et des variables dans une pop up permettant de ne pas polluer votre page.

#### Ajout de Log:
~~~
/*
* (string) $log :
*       Correspond au log à ajouter
*/

$dd->addDebugLog($log);
~~~

#### Ajout de Variable:
~~~
/*
* $var :
*       Correspond à la variable à affichée
* (string) $varName :
*       Correspond au nom affiché de la variable
* (string) [OPTIONNEL] $style :
*       Correspond au style d'affichage de la variable. 
*       Les options de style sont similaires à celles de la fonction dump()
*/

$dd->addDebugVar($var, $varName, $style);
~~~

#### Affichage de la barre de débug:
~~~

~~~
