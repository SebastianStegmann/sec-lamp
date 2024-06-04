<?php 
    // SETUP for LANGUAGE

    $en = '<svg class="w-4 h-4 " aria-hidden="true" viewBox="0 0 3900 3900"><path fill="#b22234" d="M0 0h7410v3900H0z"/><path d="M0 450h7410m0 600H0m0 600h7410m0 600H0m0 600h7410m0 600H0" stroke="#fff" stroke-width="300"/><path fill="#3c3b6e" d="M0 0h2964v2100H0z"/><g fill="#fff"><g id="d"><g id="c"><g id="e"><g id="b"><path id="a" d="M247 90l70.534 217.082-184.66-134.164h228.253L176.466 307.082z"/><use xlink:href="#a" y="420"/><use xlink:href="#a" y="840"/><use xlink:href="#a" y="1260"/></g><use xlink:href="#a" y="1680"/></g><use xlink:href="#b" x="247" y="210"/></g><use xlink:href="#c" x="494"/></g><use xlink:href="#d" x="988"/><use xlink:href="#c" x="1976"/><use xlink:href="#e" x="2470"/></g></svg>
    English';
    
    $it = '<svg class="w-4 h-4 " aria-hidden="true" id="flag-icon-css-it" viewBox="0 0 512 512"><g fill-rule="evenodd" stroke-width="1pt"><path fill="#fff" d="M0 0h512v512H0z"/><path fill="#009246" d="M0 0h170.7v512H0z"/><path fill="#ce2b37" d="M341.3 0H512v512H341.3z"/></g></svg>              
    Italiano';
    
    $de = ' <svg class="w-4 h-4" aria-hidden="true" id="flag-icon-css-de" viewBox="0 0 512 512"><path fill="#ffce00" d="M0 341.3h512V512H0z"/><path d="M0 0h512v170.7H0z"/><path fill="#d00" d="M0 170.7h512v170.6H0z"/></svg>
    Deutsch ';

    $da = '<svg class="w-4 h-4" viewBox="0 0 280 280"><rect width="370" height="280" fill="#c60c30"/><rect width="40" height="280" x="80" fill="#fff"/><rect width="370" height="40" y="120" fill="#fff"/></svg>
    Dansk';

    $es = '<svg class="w-4 h-4" viewBox="0 0 512 512">
    <path fill="#AA151B" d="M0 0h512v512H0z"/>
    <path fill="#F1BF00" d="M0 148h512v236H0z"/>
  </svg>        
    Español';
    

    $languages = [
        'en' => '<svg class="w-4 h-4 " aria-hidden="true" viewBox="0 0 3900 3900"><path fill="#b22234" d="M0 0h7410v3900H0z"/><path d="M0 450h7410m0 600H0m0 600h7410m0 600H0m0 600h7410m0 600H0" stroke="#fff" stroke-width="300"/><path fill="#3c3b6e" d="M0 0h2964v2100H0z"/><g fill="#fff"><g id="d"><g id="c"><g id="e"><g id="b"><path id="a" d="M247 90l70.534 217.082-184.66-134.164h228.253L176.466 307.082z"/><use xlink:href="#a" y="420"/><use xlink:href="#a" y="840"/><use xlink:href="#a" y="1260"/></g><use xlink:href="#a" y="1680"/></g><use xlink:href="#b" x="247" y="210"/></g><use xlink:href="#c" x="494"/></g><use xlink:href="#d" x="988"/><use xlink:href="#c" x="1976"/><use xlink:href="#e" x="2470"/></g></svg> <p>English</p>',

        'da' => '<svg class="w-4 h-4" viewBox="0 0 280 280"><rect width="370" height="280" fill="#c60c30"/><rect width="40" height="280" x="80" fill="#fff"/><rect width="370" height="40" y="120" fill="#fff"/></svg> <p>Dansk</p>',

        'it' => '<svg class="w-4 h-4 " aria-hidden="true" id="flag-icon-css-it" viewBox="0 0 512 512"><g fill-rule="evenodd" stroke-width="1pt"><path fill="#fff" d="M0 0h512v512H0z"/><path fill="#009246" d="M0 0h170.7v512H0z"/><path fill="#ce2b37" d="M341.3 0H512v512H341.3z"/></g></svg>              <p>Italiano</p>',

        'de' => ' <svg class="w-4 h-4" aria-hidden="true" id="flag-icon-css-de" viewBox="0 0 512 512"><path fill="#ffce00" d="M0 341.3h512V512H0z"/><path d="M0 0h512v170.7H0z"/><path fill="#d00" d="M0 170.7h512v170.6H0z"/></svg> <p>Deutsch</p> ',

        'es' => '<svg class="w-4 h-4" viewBox="0 0 512 512"><path fill="#AA151B" d="M0 0h512v512H0z"/><path fill="#F1BF00" d="M0 148h512v236H0z"/></svg> <p>Español</p>',
    ];

    
    // on initializing check if there is a session language 
    if (isset($_SESSION['lang'])) {
        // if there is session, set content to that language
        $_SESSION['lang'];
    } else {
        // else let content be english as standard
        $_SESSION['lang'] = 'en';
    }

    $allowed_languages = ['en', 'it', 'de', 'da', 'es'];

    if (isset($_GET['lang'])) {
    
        if (!in_array($_GET['lang'], $allowed_languages)) {
            header('Location: /'); // Redirect to the root URL if the language is not allowed
            exit();
        }
    
        // Set the session language based on the valid 'lang' parameter
        switch ($_GET['lang']) {
            case 'en':
                $_SESSION['lang'] = 'en';
                // $_SESSION['lg'] = 'en';
                break;
            case 'it':
                $_SESSION['lang'] = 'it';
                // $_SESSION['lg'] = 'it';
                break;
            case 'de':
                $_SESSION['lang'] = 'de';
                // $_SESSION['lg'] = 'de';
                break;
            case 'es':
                $_SESSION['lang'] = 'es';
                // $_SESSION['lg'] = 'es';
                break;
            case 'da':
                $_SESSION['lang'] = 'da';
                // $_SESSION['lg'] = 'da';
                break;

        }
    
        header('Location: ' . strtok($_SERVER['REQUEST_URI'], '?'));
        exit();
    } 
    // If 'lang' parameter is not provided, use the session language or default to English
    $lg = isset($_SESSION['lang']) ? $_SESSION['lang'] : 'en';
    
?>


