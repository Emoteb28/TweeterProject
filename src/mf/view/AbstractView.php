<?php
namespace mf\view;

abstract class AbstractView {

    static protected $style_sheets = []; /* un tableau de fichiers style */
    static protected $app_title    = "MF app Title"; /* un titre de document */
    protected $data        = null; /* le modÃ¨le de donnÃ©es nÃ©cessaire */
    
    /* Constructeur 
     * 
     * ParamÃ¨tres :  
     *
     * - $data (mixed) : selon la vue, une instance d'un modÃ¨le ou un tableau 
     *                   d'instances d'un modÃ¨le
     *  Algorithme 
     *  
     * - Stocker les donnÃ©es passÃ©es en paramÃ¨tre dans l'attribut $data.
     *   
     *
     */

    public function __construct( $data ){
        $this->data = $data;
    }
    
    /* MÃ©thode addStyleSheet
     * 
     * Permet d'ajouter une feuille de style Ã  la liste:
     * 
     * ParamÃ¨tres : 
     *
     * - $path_to_css_files (String) : le chemin vers le fichier 
     *                                 (relatif au script principal)
     *
     *
     */

    static public function addStyleSheet($path_to_css_files){
        self::$style_sheets[] = $path_to_css_files;
    }

    /* MÃ©thode setAppTitle 
     * 
     * Permet de stocker un nom pour l'application (afficher sur le navigateur)
     * c'est le le titre du document HTML 
     *     
     * ParamÃ¨tres : 
     *
     * - $title (String) : le titre du document HTML
     * 
     */
    
    static public function setAppTitle($title){
        self::$app_title = $title;
    }

    /* MÃ©thode renderBody 
     * 
     * Retourne le contenu HTML de la 
     * balise body autrement dit le contenu du document. 
     *
     * Elle prend un sÃ©lecteur en paramÃ¨tre dont la 
     * valeur indique quelle vue il faut gÃ©nÃ©rer.
     * 
     * Note cette mÃ©thode est a dÃ©finir dans les classes concrÃ¨tes des vues, 
     * elle est appelÃ©e depuis la mÃ©thode render ci-dessous.
     * 
     * ParamÃ¨tre : 
     * 
     * $selector (String) : une chaÃ®ne qui permet de savoir quelle vue gÃ©nÃ©rer
     * 
     * Retourne : 
     *
     * - (String) : le contenu HTML complet entre les balises <body> </body> 
     *
     */
    
    abstract public function renderBody($selector);
    
    /* MÃ©thodes render
     * 
     * cette mÃ©thode gÃ©nÃ¨re le code HTML d'une page complÃ¨te depuis le <doctype 
     * jusqu'au </html>. 
     * 
     * Elle dÃ¨finit les entÃªtes HTML, le titre de la page et lie les feuilles 
     * de style. le contenu du document est rÃ©cupÃ©rÃ© depuis les mÃ©thodes 
     * renderBody des sous classe. 
     *
     * Elle utilise la syntaxe HEREDOC pour dÃ©finir un patron et
     * l'Ã©crire la chaine de caractÃ¨re de la page entiÃ¨re. Voir la
     * documentation ici:
     *
     * http://php.net/manual/fr/language.types.string.php#language.types.string.syntax.heredoc
     *
     */
    
    public function render($selector){
        /* le titre du document */
        $title = self::$app_title;

        /* les feuilles de style */
        $app_root = (new \mf\utils\HttpRequest())->root;
        $styles = '';
        foreach ( self::$style_sheets as $file )
            $styles .= '<link rel="stylesheet" href="'.$app_root.'/'.$file.'"> ';

        /* on appele la methode renderBody de la sous classe */
        $body = $this->renderBody($selector);
        

        /* construire la structure de la page 
         * 
         *  Noter l'utilisation des variables ${title} ${style} et ${body}
         * 
         */
                
        $html = <<<EOT
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <title>${title}</title>
	    ${styles}
    </head>

    <body>
        
       ${body}

    </body>
</html>
EOT;

        echo $html;
    }
    
}