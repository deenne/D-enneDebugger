# DeenneDebugger
DéenneDébugger est une class PHP vous permettant de simplifier le débuggage de vos sites en permettant un affichage de variables, l'ajout de logs ainsi qu 'un calcul du temps de traitement de votre script.
-----

## Usage :

### Initialisation:
Pour initialiser la class vous devez ajouter un *require* au début de votre fichier et créer une instance.
~~~
/*
* CHEMIN A ADAPTER SELON VOTRE STRUCTURE DE FICHIER
*/
require "./DeenneDebugger.php";

$dd = new DeenneDebugger();
~~~

### Affichage d'une variable:
L'affichage de variables est inspiré de Symfony, pour cela nous avons deux fonctions.
~~~
/*
* AFFICHAGE DE LA VARIABLE
*
* $variableAAfficher :
*       Correspond à la variable à afficher
*
* (string) [OPTIONNEL] $style :
*       Correspond au style d'affichage de la variable.
*       Options disponibles :
*           - INFO (fond bleu)
*           - VALID (fond vert)
*           - ALERT (fond jaune)
*           - ERROR (fond rouge)
*
* (bool) [OPTIONNEL] $return :
*       Par défaut à false
*       Si mis à true le contenu par défaut renvoyé dans un echo sera renvoyé avec un return
*/
$dd->dump($variableAAfficher, $style, $return);
~~~

~~~
/*
* AFFICHAGE DE LA VARIABLE & ARRÊT DU SCRIPT
*
* $variableAAfficher :
*       Correspond à la variable à afficher
*
* (string) [OPTIONNEL] $style :
*       Correspond au style d'affichage de la variable.
*       Options disponibles :
*           Similaire à dump()
*/
$dd->dd($variableAAfficher, $style);
~~~

### Calcul du temps de traitement :
Le calcul du temps de traitement nécéssite deux fonctions, la première doit être exécutée avant ce dont vous souhaitez obtenir le temps de traitement et la seconde après.
~~~
/*
* DEBUT DU SCRIPT || DE LA FONCTION
*/
$dd->startLoad();

/*
* Script ou fonction dont on cherche à obtenir le temps de traitement
*/

/*
* FIN DU SCRIPT || DE LA FONCTION
*
* (bool) [OPTIONNEL] $print :
*       Par défaut à false
*       Si mis à true la fonction affiche le temps de traitement au lieu de le retourner
*
* (string) [OPTIONNEL] $style :
*       Correspond au style d'affichage
*       Options disponibles :
*           Similaire à dump()
*/
$tempsTraitement = $dd->endLoad($print, $style);
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
*       Correspond à la variable à afficher
*
* (string) $varName :
*       Correspond au nom affiché de la variable
*
* (string) [OPTIONNEL] $style :
*       Correspond au style d'affichage de la variable.
*       Options disponibles :
*           Similaire à dump()
*/

$dd->addDebugVar($var, $varName, $style);
~~~

#### Affichage de la barre de débug:
Fonction à exécuter juste avant la fermeture de la balise </body> de votre fichier
~~~
$dd->debugBarre();
~~~
