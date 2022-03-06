<?php // CLASS
    class DeenneDebugger
    {
        protected $pageLoad;
        protected $pageLoadStart;
        protected $pageLoadEnd;
        
        protected $debugBarLoad;
        protected $debugBarLoadStart;
        protected $debugBarLoadEnd;

        protected $debugVars = [];

        protected $debugLogs = "";

        public function __construct() 
        {
            $this->debugBarLoadStart = microtime(true);
        }

        public function dump($var=null, $style="", $return=false)
        {
            if ($style == "ERROR")
            {
                $style = " background-color: #972121; color: #ccc; font-weight: bold;";
            }
            if ($style == "ALERT")
            {
                $style = " background-color: #a7842c; color: #ccc; font-weight: bold;";
            }
            if ($style == "VALID")
            {
                $style = " background-color: #219721; color: #ccc; font-weight: bold;";
            }
            if ($style == "INFO")
            {
                $style = " background-color: #212197; color: #ccc; font-weight: bold;";
            }

            $html = '<pre style="white-space: pre-wrap; background-color: #212121; color: #ccc; padding: 7px; border: 2px solid #ccc; border-radius: 3px; margin: 10px 0px; overflow-x:auto;'.$style.'">';

            if (is_array($var))
            {
                $html .= $this->prettyPrintR($var, 0, 1);
            }
            elseif (is_string($var) || is_int($var))
            {
                $html .= $var;
            }
            else
            {
                $html .= $var;
            }

            $html .= '</pre>';

            if ($return == false) {
                echo $html;
            }
            else 
            {
                return $html;
            }
        }

        public function dd($var=null, $style="")
        {
            $this->dump($var, $style);

            die();
        }

        public function prettyPrintR($array, $baseIndent=0, $modeDump=0): string
        {
            $html = "";
            $open = "open";

            foreach ($array as $k => $v)
            {
                $indent = (($baseIndent+1) * 35) +5;
                $indent = $indent."px";

                if (is_array($v))
                {
                    $data = $this->prettyPrintR($v, $baseIndent+1);

                    if (!empty($data))
                    {
                        $html .= "\n<details $open><summary style='display:inline-block; text-indent: $indent; cursor: pointer;'><span style='color:#FFD533; font-weight: bold;'>[$k]</span> => <span style='color:#; font-weight: bold;'>[</span> <span style='color:#ff7e7e; font-weight: bold;'>:Array</span></summary>\n";
                        $html .=  $data . "</details>";
                        $html .= "<span style='display:inline-block; text-indent: $indent; color:#; font-weight: bold;'>]</span>\n";
                    }
                    else
                    {
                        $html .= "<span style='display:inline-block; text-indent: $indent;'><span style='color:#6ea6ff; font-weight: bold;'>[$k]</span> => </span>\n";
                    }
                }
                else
                {
                    $html .= "<span style='display:inline-block; text-indent: $indent;'><span style='color:#6ea6ff; font-weight: bold;'>[$k]</span> => $v</span>\n";
                }
            }

            if ($baseIndent==0)
            {
                if ($modeDump == 1)
                {
                    $html = '<pre style="white-space: pre-wrap;"><code style="color: #fff;">['. "\n" . $html .']</code></pre>';
                }
                else
                {
                    $html = '<pre style="padding: 10px; background-color: #212121; color: #fff; white-space: pre-wrap; border-radius: 3px;"><code style="color: #fff;">['. "\n" . $html .']</code></pre>';
                }
            }

            return $html;
        }

        function startLoad()
        {
            $this->pageLoadStart = microtime(true);
        }
        function endLoad($print=false, $style="")
        {
            $this->pageLoadEnd = microtime(true);

            $this->pageLoad = $this->pageLoadEnd - $this->pageLoadStart;

            if ($print==false)
            {
                return round($this->pageLoad, 3);
            }
            else
            {
                if ($this->pageLoad / 60 > 1)
                {
                    $this->dump("Temps de traitement: " . round($this->pageLoad / 60, 3) . ' minutes', $style);
                }
                else
                {
                    $this->dump("Temps de traitement: " . round($this->pageLoad) . ' secondes', $style);
                }
            }
        }

        public function debugBarre($showServerAndSession = true)
        {
            $this->debugBarLoadEnd = microtime(true);
            $this->debugBarLoad = $this->debugBarLoadEnd - $this->debugBarLoadStart;

            if (!empty($this->debugLogs)) 
            {
                $this->addDebugLog("Script exécuté en " . round($this->debugBarLoad, 3) . 's');
            }
            else 
            {
                $this->debugLogs .= "Script exécuté en " . round($this->debugBarLoad, 3) . 's';
            }

            ob_start();
?>

    <!-- OUVERTURE DE LA BARRE -->
    <div 
        style="
            position:fixed;
            bottom:10px;
            right:10px;
            width:25px;
            height:25px;
            background-color:#000;
            cursor:pointer;
            display:flex;
            align-items:center;
            justify-content:center;
            z-index:3000;
            box-sizing:border-box;
        "
        onclick="
            getElementById('ddDebugBarre').hidden = false;
            getElementById('ddDebugVars').hidden = false;
        "
    >
        <img 
            src="
                data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyMy4wLjMsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB4PSIwcHgiIHk9IjBweCINCgkgdmlld0JveD0iMCAwIDMxLjcgMjYuNCIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMzEuNyAyNi40OyIgeG1sOnNwYWNlPSJwcmVzZXJ2ZSI+DQo8c3R5bGUgdHlwZT0idGV4dC9jc3MiPg0KCS5zdDB7ZmlsbDojRkZGRkZGO30NCjwvc3R5bGU+DQo8ZyBpZD0iQ2FscXVlXzIiPg0KPC9nPg0KPGcgaWQ9IkNhbHF1ZV8xIj4NCgk8Zz4NCgkJPGc+DQoJCQk8Zz4NCgkJCQk8cGF0aCBjbGFzcz0ic3QwIiBkPSJNMzEuNywyLjZjMCwxLjQtMS4yLDIuNi0yLjYsMi42SDIuNkMxLjIsNS4yLDAsNCwwLDIuNlMxLjIsMCwyLjYsMGgyNi41QzMwLjYsMCwzMS43LDEuMiwzMS43LDIuNnoiLz4NCgkJCTwvZz4NCgkJPC9nPg0KCQk8Zz4NCgkJCTxnPg0KCQkJCTxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik0zMS43LDEzLjJjMCwxLjQtMS4yLDIuNi0yLjYsMi42SDIuNmMtMS40LDAtMi42LTEuMi0yLjYtMi42YzAtMS40LDEuMi0yLjYsMi42LTIuNmgyNi41DQoJCQkJCUMzMC42LDEwLjYsMzEuNywxMS44LDMxLjcsMTMuMnoiLz4NCgkJCTwvZz4NCgkJPC9nPg0KCQk8Zz4NCgkJCTxnPg0KCQkJCTxwYXRoIGNsYXNzPSJzdDAiIGQ9Ik0zMS43LDIzLjhjMCwxLjQtMS4yLDIuNi0yLjYsMi42SDIuNmMtMS40LDAtMi42LTEuMi0yLjYtMi42czEuMi0yLjYsMi42LTIuNmgyNi41DQoJCQkJCUMzMC42LDIxLjIsMzEuNywyMi40LDMxLjcsMjMuOHoiLz4NCgkJCTwvZz4NCgkJPC9nPg0KCTwvZz4NCjwvZz4NCjwvc3ZnPg0K
            " 
            alt="menu" 
            style="
                width:15px;
                max-height:15px;
                box-sizing:border-box;
            "
        />
    </div>

    <!-- AFFICHAGE D'INFOS DES VARIABLES -->
    <div 
        id="ddDebugVars"
        class="ddDebugDiv"
        style="
            position:fixed;
            top:5px;
            bottom:5px;
            left:5px;
            right:50px;
            background-color:#000;
            z-index:3001;
            box-sizing:border-box;
            padding: 15px;
            padding-top:35px;
            font-family:sans-serif;
            color:#fff;
            font-size: 18px;
        "
        hidden
    >
        <div 
            style="
                position:absolute;
                top:35px;
                bottom:15px;
                left:15px;
                box-sizing:border-box;
                width:calc(75% - 20px);
                overflow-x:hidden;
                overflow-y:auto;
            "
        >
            <div 
                class="ddDebugVarDiv"
                id="DDLOGS"
            >
                <div
                    style="
                        font-family:sans-serif;
                        color:#fff;
                        font-size: 26px;
                        font-weight:bold;
                    "
                >
                    Logs
                </div>

                <br>

                <?php
                    $this -> dump($this->debugLogs)
                ?>
            </div>

            <?php
                $hidden = "hidden";

                foreach ($this->debugVars as $var) 
                {
                    echo '<div 
                        class="ddDebugVarDiv"
                        id="'.$this->createId($var['name']).'"
                        '.$hidden.'
                    >
                        <div
                            style="
                                font-family:sans-serif;
                                color:#fff;
                                font-size: 26px;
                                font-weight:bold;
                            "
                        >
                            '.$var['name'].'
                        </div>

                        <br>
                        ' . 
                        $this -> dump(
                            $var['value'],
                            $var['style'],
                            true
                        ) 
                    . '</div>';

                    $hidden = "hidden";
                }
            ?>
            
            <div 
                class="ddDebugVarDiv"
                id="GET"
                hidden
            >
                <div
                    style="
                        font-family:sans-serif;
                        color:#fff;
                        font-size: 26px;
                        font-weight:bold;
                    "
                >
                    $_GET
                </div>

                <br>

                <?php
                    $this -> dump($_GET)
                ?>
            </div>
            
            <div 
                class="ddDebugVarDiv"
                id="POST"
                hidden
            >
                <div
                    style="
                        font-family:sans-serif;
                        color:#fff;
                        font-size: 26px;
                        font-weight:bold;
                    "
                >
                    $_POST
                </div>

                <br>

                <?php
                    $this -> dump($_POST)
                ?>
            </div>
            
            <div 
                class="ddDebugVarDiv"
                id="SESSION"
                hidden
            >
                <div
                    style="
                        font-family:sans-serif;
                        color:#fff;
                        font-size: 26px;
                        font-weight:bold;
                    "
                >
                    $_SESSION
                </div>

                <br>

                <?php
                    if ($showServerAndSession == true) 
                    {
                        $this -> dump($_SESSION);
                    }
                    else 
                    {
                        $this -> dump("Variable masquée", "INFO");
                    }
                ?>
            </div>
            
            <div 
                class="ddDebugVarDiv"
                id="SERVER"
                hidden
            >
                <div
                    style="
                        font-family:sans-serif;
                        color:#fff;
                        font-size: 26px;
                        font-weight:bold;
                    "
                >
                    $_SERVER
                </div>

                <br>

                <?php
                    if ($showServerAndSession == true)
                    {
                        $this -> dump($_SERVER);
                    }
                    else 
                    {
                        $this -> dump("Variable masquée", "INFO");
                    }
                ?>
            </div>
        </div>

        <div
            style="
                position:absolute;
                top:35px;
                bottom:15px;
                right:15px;
                box-sizing:border-box;
                width:calc(25% - 20px);
                overflow-x:hidden;
                overflow-y:auto;
            "
        >
            <div>
                <div 
                    class="ddDebugVarName"
                    style="
                        box-sizing:border-box;
                        overflow:hidden;
                        margin-bottom:5px;
                        background-color:#212121;
                        padding:10px;
                        cursor:pointer;
                        font-weight:bold;
                        white-space:nowrap;
                    "
                    onclick="
                        var elements = document.getElementsByClassName('ddDebugVarDiv');

                        for (let i = 0; i < elements.length; i++) 
                        {
                            elements[i].hidden = true;
                        }

                        getElementById('DDLOGS').hidden = false;
                    "
                >
                    Logs
                </div>

                <?php
                    foreach ($this->debugVars as $var) 
                    {
                        echo '<div 
                            class="ddDebugVarName"
                            style="
                                box-sizing:border-box;
                                overflow:hidden;
                                margin-bottom:5px;
                                background-color:#212121;
                                padding:10px;
                                cursor:pointer;
                                font-weight:bold;
                                white-space:nowrap;
                            "
                            onclick="
                                var elements = document.getElementsByClassName(\'ddDebugVarDiv\');

                                for (let i = 0; i < elements.length; i++) 
                                {
                                    elements[i].hidden = true;
                                }

                                getElementById(\''.$this->createId($var['name']).'\').hidden = false;
                            "
                        >
                            '.$var['name'].'
                        </div>';
                    }
                ?>
                
                <div 
                    class="ddDebugVarName"
                    style="
                        box-sizing:border-box;
                        overflow:hidden;
                        margin-bottom:5px;
                        background-color:#212121;
                        padding:10px;
                        cursor:pointer;
                        font-weight:bold;
                        white-space:nowrap;
                    "
                    onclick="
                        var elements = document.getElementsByClassName('ddDebugVarDiv');

                        for (let i = 0; i < elements.length; i++) 
                        {
                            elements[i].hidden = true;
                        }

                        getElementById('GET').hidden = false;
                    "
                >
                    $_GET
                </div>
                
                <div 
                    class="ddDebugVarName"
                    style="
                        box-sizing:border-box;
                        overflow:hidden;
                        margin-bottom:5px;
                        background-color:#212121;
                        padding:10px;
                        cursor:pointer;
                        font-weight:bold;
                        white-space:nowrap;
                    "
                    onclick="
                        var elements = document.getElementsByClassName('ddDebugVarDiv');

                        for (let i = 0; i < elements.length; i++) 
                        {
                            elements[i].hidden = true;
                        }

                        getElementById('POST').hidden = false;
                    "
                >
                    $_POST
                </div>
                
                <div 
                    class="ddDebugVarName"
                    style="
                        box-sizing:border-box;
                        overflow:hidden;
                        margin-bottom:5px;
                        background-color:#212121;
                        padding:10px;
                        cursor:pointer;
                        font-weight:bold;
                        white-space:nowrap;
                    "
                    onclick="
                        var elements = document.getElementsByClassName('ddDebugVarDiv');

                        for (let i = 0; i < elements.length; i++) 
                        {
                            elements[i].hidden = true;
                        }

                        getElementById('SESSION').hidden = false;
                    "
                >
                    $_SESSION
                </div>
                
                <div 
                    class="ddDebugVarName"
                    style="
                        box-sizing:border-box;
                        overflow:hidden;
                        margin-bottom:5px;
                        background-color:#212121;
                        padding:10px;
                        cursor:pointer;
                        font-weight:bold;
                        white-space:nowrap;
                    "
                    onclick="
                        var elements = document.getElementsByClassName('ddDebugVarDiv');

                        for (let i = 0; i < elements.length; i++) 
                        {
                            elements[i].hidden = true;
                        }

                        getElementById('SERVER').hidden = false;
                    "
                >
                    $_SERVER
                </div>
            </div>
        </div>
    </div>

    <!-- BARRE -->
    <div 
        id="ddDebugBarre"
        style="
            position:fixed;
            top:0px;
            bottom:0px;
            right:0px;
            width:45px;
            background-color:#000;
            z-index:3001;
            box-sizing:border-box;
            padding: 5px;
            font-family:sans-serif;
            color:#fff;
            font-size: 20px;
            font-weight:bold;
            user-select:none;
        "
        hidden
    >
        <div
            style="
                width:100%;
                height:calc(100% - 45px);
                box-sizing:border-box;
                overflow-x:hidden;
                overflow-y:auto;
            "
        >
            <!-- // PICTOS DU MENU -->

            <div 
                style="
                    margin-bottom:5px;
                    box-sizing:border-box;
                    width:100%;
                    height:35px;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                "
            >
                <span>D</span>
            </div>

            <div 
                style="
                    margin-bottom:5px;
                    cursor:pointer;
                    box-sizing:border-box;
                    width:100%;
                    height:35px;
                    display:flex;
                    align-items:center;
                    justify-content:center;
                "
                onclick="
                    var newVisibility = !getElementById('ddDebugVars').hidden;
                    document.getElementsByClassName('ddDebugDiv').hidden = true;
                    getElementById('ddDebugVars').hidden = newVisibility;
                "
            >
                <span>$</span>
            </div>
        </div>
        
        <img 
            src="
                data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4NCjwhLS0gR2VuZXJhdG9yOiBBZG9iZSBJbGx1c3RyYXRvciAyMy4wLjMsIFNWRyBFeHBvcnQgUGx1Zy1JbiAuIFNWRyBWZXJzaW9uOiA2LjAwIEJ1aWxkIDApICAtLT4NCjxzdmcgdmVyc2lvbj0iMS4xIiBpZD0iQ2FscXVlXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4Ig0KCSB2aWV3Qm94PSIwIDAgODAgODAuMSIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgODAgODAuMTsiIHhtbDpzcGFjZT0icHJlc2VydmUiPg0KPHN0eWxlIHR5cGU9InRleHQvY3NzIj4NCgkuc3Qwe2ZpbGwtcnVsZTpldmVub2RkO2NsaXAtcnVsZTpldmVub2RkO2ZpbGw6I0ZGRkZGRjt9DQo8L3N0eWxlPg0KPHBhdGggY2xhc3M9InN0MCIgZD0iTTc2LjksMTcuOEM4MSwxMy43LDgxLDcuMSw3Ni45LDNjLTQtNC0xMC43LTQtMTQuNywwLjFMNDAsMjUuMkwxNy44LDMuMUMxMy44LTEsNy4xLTEsMy4xLDMuMQ0KCXMtNC4xLDEwLjcsMCwxNC44TDI1LjIsNDBMMy4xLDYyLjJjLTQuMSw0LTQuMSwxMC43LDAsMTQuN2M0LjEsNC4xLDEwLjcsNC4xLDE0LjgsMEw0MCw1NC44TDYyLjIsNzdjNC4xLDQuMSwxMC43LDQuMSwxNC44LDANCglzNC4xLTEwLjcsMC0xNC44TDU0LjgsNDBMNzYuOSwxNy44eiIvPg0KPC9zdmc+DQo=
            " 
            alt="Fermer le menu"
            style="
                box-sizing:border-box;
                position:absolute;
                bottom:15px;
                right:15px;
                width:15px;
                height:15px;
                cursor:pointer;
            "
            onclick="
                getElementById('ddDebugBarre').hidden = true;
                var elements = document.getElementsByClassName('ddDebugDiv');

                for (let i = 0; i < elements.length; i++) 
                {
                    elements[i].hidden = true;
                }
            "
        >
    </div>

<?php  
        // Suite de la fonction debugBarre()
            $contents = ob_get_contents();
            ob_end_clean();

            echo $contents;
        }

        public function addDebugVar($var, $varname, $style="")
        {
            $this->debugVars[] = [
                "name" => $varname,
                "value" => $var,
                "style" => $style
            ];
        }

        public function getDebugVar()
        {
            return $this->debugVars;
        }

        public function createId($str)
        {
            $encrypt_method = "AES-256-CBC";
            $secret_key = "6Ucf48D2zP";
            $secret_iv = "S7h7H4gPh3";

            // hash
            $key = hash('sha256', $secret_key);
            $iv = substr(hash('sha256', $secret_iv), 0, 16);

            $output = openssl_encrypt($str, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
            $output = str_replace('=', '', $output);

            return $output;
        }

        public function addDebugLog($log)
        {
            $debugLoadEnd = microtime(true);
            $debugLoad = round($debugLoadEnd - $this->debugBarLoadStart, 3);

            $this->debugLogs .= "\n\n<span style='font-weight: bold;'>".$debugLoad . "s :</span> " . $log;
        }
    }
// FIN CLASS ?>
