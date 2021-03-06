<?php

	session_start();
	mysql_connect("localhost", "root", "");
	mysql_select_db("stargatearrow");

// Fonction pour sécuriser toutes les entrées en base de données
	function secur($secur) {
		return mysql_real_escape_string($secur);
	}

// Fonction pour sécuriser les données sorties de la base de données
	function sortie($sortie) {
		return stripslashes(htmlspecialchars($sortie));
	}
	
// Fonction pour faire les requêtes SQLs (en même temps ça compte le nb de requêtes/page)
	$nbQuery = 0;
	function query($query) {
		global $nbQuery;
		$nbQuery++;
	return mysql_query($query);
	}

// Fonction assoc (qui sert juste à écrire mysql_fetch_assoc() plus vite)
	function assoc($assoc) {
		return mysql_fetch_assoc($assoc);
	}
	
// Fonction BBcode : fonction qui sera utilisée pour mettre en forme les textes envoyés par les mbr
function BBcode($content) {
	// Parsage des balises
$BBcode = array(  
		'`\[gras\](.+)\[/gras\]`isU',
		'`\[italique\](.+)\[/italique\]`isU',
		'`\[souligner\](.+)\[/souligner\]`isU',
		'`\[barrer\](.+)\[/barrer\]`isU',
		'`\[citation auteur=&quot;(.+)&quot;\](.+)\[/citation\]`isU',
		'`\[citation\](.+)\[/citation\]`isU',
		'`\[image nom=&quot;(.+)&quot;\](.+)\[/image\]`isU',
		'`\[icone nom=&quot;(.+)&quot;\](.+)\[/icone\]`isU',
		'`\[lien\](.+)\[/lien\]`isU',
		'`\[lien url=&quot;(.+)&quot;\](.+)\[/lien\]`isU',
		'`\[email\](.+)\[/email\]`isU',
		'`\[email adresse=&quot;(.+)&quot;\](.+)\[/email\]`isU',
		'`\[position valeur=&quot;(.+)&quot;\](.+)\[/position\]`isU',
		'`\[taille valeur=&quot;(.+)&quot;\](.+)\[/taille\]`isU',
		'`\[flottant valeur=&quot;(.+)&quot;\](.+)\[/flottant\]`isU',
		'`\[couleur valeur=&quot;(.+)&quot;\](.+)\[/couleur\]`isU',
		'`\[police valeur=&quot;(.+)&quot;\](.+)\[/police\]`isU',
		'`\[exposant\](.+)\[/exposant\]`isU',
		'`\[indice\](.+)\[/indice\]`isU',
		'`\[youtube\](.+)\[/youtube\]`isU',
		'`\[titre1\](.+)\[/titre1\]`isU',
		'`\[titre2\](.+)\[/titre2\]`isU',
	);  
	
	$html = array(  
		'<span class="gras">$1</span>',
		'<span class="italique">$1</span>',
		'<span class="souligner">$1</span>',
		'<span class="barrer">$1</span>',
		'<div class="citation"><span class="gras">Citation de $1 :</span><br />$2</div>',
		'<div class="citation"><span class="gras">Citation :</span><br />$1</div>',
		'<img src="../$2" alt="$1" />',
		'<a href="$2"><img src="$2" alt="$1" width="100px" height="80px"  /></a>',
		'<a href="#" class="lien-navigateur" title="$1" >$1</a>',
		'<a href="#" class="lien-navigateur" title="$1" >$2</a>',
		'<a href="mailto: $1">$1</a>',
		'<a href="mailto: $1">$2</a>',
		'<div class="$1">$2</div>',
		'<span class="$1">$2</span>',
		'<div class="flottant$1">$2</div>',
		'<span class="$1">$2</span>',
		'<span class="$1">$2</span>',
		'<sup>$1</sup>',
		'<sub>$1</sub>',
		'<iframe width="560" height="315" src="$1" frameborder="0" allowfullscreen></iframe>',
		'<h2>$1</h2>',
		'<h3>$1</h3>',
	); 
	
	$content = preg_replace($BBcode, $html, $content);
	
	// parsage des smilies
	$smiliesName = array('o_O', '\^\^', ':colere:', ':euh:', ':D', ':p', '-_-', ':\'', ':rire:', ':hum:', ';\)', ':\)', ':\(', 'x_x');
	$smiliesUrl  = array('blink.png', 'ciel.png', 'colere.png', 'heu.png', 'heureux.png', 'langue.png', 'oups.png', 'pleure.png', 'rire.png', 'hum.png', 'clin.png', 'smile.png', 'triste.png', 'x_x.png');
	$smiliesPath = "Templates/Images/Smilies/";
	
	for ($i = 0, $c = count($smiliesName); $i < $c; $i++) {
		$content = preg_replace('`' . $smiliesName[$i] . '`isU', '<img src="' . $smiliesPath . $smiliesUrl[$i] . '" alt="smiley" />', $content);
	}

	// Rtours à la ligne
	$content = preg_replace('`\n`isU', '<br />', $content); 

	return $content;
}

// Fonction pour remplacer tout ce qui n'est ni chiffre ni lettre par un tiret (fonction pratique pour les URLs)
	function replace($replace) {
		$endReplace = str_replace('é', 'e', $replace);
		$endReplace = str_replace('è', 'e', $endReplace);
		$endReplace = str_replace('ê', 'e', $endReplace);
		$endReplace = str_replace('ë', 'e', $endReplace);
		$endReplace = str_replace('â', 'a', $endReplace);
		$endReplace = str_replace('ä', 'a', $endReplace);
		$endReplace = str_replace('à', 'a', $endReplace);
		$endReplace = str_replace('ï', 'i', $endReplace);
		$endReplace = str_replace('î', 'i', $endReplace);
		$endReplace = str_replace('ü', 'u', $endReplace);
		$endReplace = str_replace('û', 'u', $endReplace);
		$endReplace = str_replace('ù', 'u', $endReplace);
		$endReplace = str_replace('Y', 'y', $endReplace);
		$endReplace = str_replace('ô', 'o', $endReplace);
		$endReplace = str_replace('ö', 'o', $endReplace);
		$endReplace = str_replace('ç', 'c', $endReplace);
		$endReplace = str_replace('?', '', $endReplace);
		$endReplace = str_replace('!', '', $endReplace);
		$endReplace = str_replace('...', '', $endReplace);
	return strtolower(preg_replace('#([^a-z0-9])+#i', '-', $endReplace));
}

$infos1 = query('SELECT * FROM news, categories_news WHERE catNews_id = news_cat AND news_validee = 1 ORDER BY news_date DESC LIMIT 0,10');
$sectionsNews = "";
$listeNews = "";
$javascriptNews = "";
$numero = 1;

while($infos = assoc($infos1)) {

  $idNews = sortie($infos['news_id']);
  $titreNews = sortie($infos['news_nom']);
  $contenuNews = BBcode(sortie($infos['news_contenu']));
  $contenuNews = preg_replace('`\.\./http`isU', 'http', $contenuNews); 
  $imageNews = sortie($infos['news_image']);
  $dateNews = sortie(date('d/m/Y', sortie($infos['news_date'])));
  //Si les liens ne sont pas en asolu, on les transforme
  if(!(strpos($imageNews,'http') !== false)) {
	$imageNews = 'http://www.green-arrow-france.fr/'.$imageNews;
  }
  $catNews = sortie($infos['catNews_nom']);

  $sectionsNews .= '<section role="region" id="news'.$numero.'" data-position="right">';
  $sectionsNews .= '<header class="fixed">';
  $sectionsNews .= '<a id="btn-news'.$numero.'-back" href="#"><span class="icon icon-back">back</span></a>';
  $sectionsNews .= '<menu type="toolbar">';
  $sectionsNews .= '<a href="#"><span class="icon icon-planet" id="btn-news-lecture'.$numero.'">site internet</span></a>';
  $sectionsNews .= '</menu>';
  $sectionsNews .= '<h1>'.$titreNews.'</h1>';
  $sectionsNews .= '</header>';
  $sectionsNews .= '<article class="content scrollable header">';
  $sectionsNews .= '<div class="lectureNews">';
  $sectionsNews .= '<h1>'.$titreNews.'</h1>';
  $sectionsNews .= 'Publié le '.$dateNews.'<br/><br/>';
  $sectionsNews .= 	$contenuNews;
  $sectionsNews .= '</div>';
  $sectionsNews .= '</article>';
  $sectionsNews .= '</section>';
  
  $listeNews .= '<li>';
  $listeNews .= '<aside class="pack-end">';
  $listeNews .= '<img alt="photo" src="'.$imageNews.'">';
  $listeNews .= '</aside>';
  $listeNews .= '<a id="btn-news'.$numero.'" href="#">';
  $listeNews .= '<p class="listeNewsTitle">'.$titreNews.'</p>';
  $listeNews .= '<p>'.$catNews.'</p>';
  $listeNews .= '</a>';
  $listeNews .= '</li>';
  
  $javascriptNews .= "document.querySelector('#btn-news".$numero."').addEventListener ('click', function () {
  document.querySelector('#news".$numero."').className = 'current';
  document.querySelector('[data-position=\"current\"]').className = 'left';
});
document.querySelector('#btn-news".$numero."-back').addEventListener ('click', function () {
  document.querySelector('#news".$numero."').className = 'right';
  document.querySelector('[data-position=\"current\"]').className = 'current';
});";
  
  //Pour ouvrir la news dans le navigateur
  $javascriptNews .= "document.querySelector('#btn-news-lecture".$numero."').addEventListener ('click', function () {";
  $javascriptNews .= "if ( isFFOS ) {";
  $javascriptNews .= "var openURL = new MozActivity({";
  $javascriptNews .= "  name: \"view\",";
  $javascriptNews .= "  data: {";
  $javascriptNews .= "      type: \"url\",";
  $javascriptNews .= "      url: \"http://www.green-arrow-france.fr/news-501-".$idNews."-".replace($titreNews).".html\"";
  $javascriptNews .= "  }";
  $javascriptNews .= "  });";
  $javascriptNews .= "} else {";
  $javascriptNews .= "	  window.location.href = \"http://www.green-arrow-france.fr/news-501-".$idNews."-".replace($titreNews).".html\"";
  $javascriptNews .= "	}";
  $javascriptNews .= "});";
  
  $numero ++;
}

?>



<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, user-scalable=no" />
  <title>Green Arrow France</title>
  <!-- Building blocks -->
  <link rel="stylesheet" href="style/headers.css">
  <link rel="stylesheet" href="style/drawer.css">
  <link rel="stylesheet" href="style/lists.css">
  <link rel="stylesheet" href="style/scrolling.css">
  <link rel="stylesheet" href="style/BBCode.css">
  <link rel="stylesheet" href="style/styleAddon.css">

  <!-- Icons -->
  <link rel="stylesheet" href="icons/styles/action_icons.css">
  <link rel="stylesheet" href="icons/styles/media_icons.css">
  <link rel="stylesheet" href="icons/styles/comms_icons.css">
  <link rel="stylesheet" href="icons/styles/settings_icons.css">

  <!-- Transitions -->
  <link rel="stylesheet" href="transitions.css">

  <!-- Util CSS: some extra tricks -->
  <link rel="stylesheet" href="util.css">

  <!-- Additional markup to make Building Blocks kind of cross browser -->
  <!--link rel="stylesheet" href="cross_browser.css"-->

  <style>
    #index {
      height: 100%;
    }
    [data-position="right"] {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      transform: translateX(100%);
      -webkit-transform: translateX(100%);
      z-index: 15;
      z-index: 100; /* -> drawer */
    }
    section[role="region"][data-position="right"] {
      position: absolute;
    }
    [data-position="right"].current {
      animation: rightToCurrent 0.4s forwards;
      -webkit-animation: rightToCurrent 0.4s forwards;
    }
    [data-position="right"].right {
      animation: currentToRight 0.4s forwards;
      -webkit-animation: currentToRight 0.4s forwards;
    }
    [data-position="current"].left {
      animation: currentToLeft 0.4s forwards;
      -webkit-animation: currentToLeft 0.4s forwards;
    }
    [data-position="current"].current {
      animation: leftToCurrent 0.4s forwards;
      -webkit-animation: leftToCurrent 0.4s forwards;
    }
    [data-position="back"] {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: -1;
      opacity: 0;
      /* z-index: 100; -> drawer */
    }
    [data-position="back"].fade-in {
      z-index: 120;
      animation: fadeIn 0.2s forwards;
      -webkit-animation: fadeIn 0.2s forwards;
    }
    [data-position="back"].fade-out {
      animation: fadeOut 0.2s forwards;
      -webkit-animation: fadeOut 0.2s forwards;
    }

    [data-position="edit-mode"] {
      position: absolute;
      top: -5rem;
      left: 0;
      right: 0;
      bottom: -7rem;
      z-index: -1;
      opacity: 0;
      transition: all 0.3s ease;
    }
    [data-position="edit-mode"].edit {
      position: absolute;
      top: 0;
      left: 0;
      right: 0;
      bottom: 0;
      z-index: 120;
      opacity: 1;
    }

    /* Active state */
    .active {
      background-color: #b2f2ff;
      color: #fff;
    }

    /* Headers */
    #headers .content {
      margin-top: -1.5rem;
    }
    #headers section[role="region"] {
      margin-bottom: 1.5rem;
    }
    #headers section[role="region"]:not(#drawer) {
      display: inline;
    }
    #headers article header:first-child {
      margin-top: 1.5rem;
    }
    #headers section[role="region"] header h2 {
      margin: 0 0 1.5rem 0;
    }

    /* Lists */
    /* to avoid double background effect on press */
    [data-type=list] li>a:active {
      background-color: transparent;
    }

    /* Drawer */
    section[role="region"]:not(#drawer) {
      transition: none;
      left: 0;
      z-index: 0;
      padding-left: 0;
    }
    section[data-type="sidebar"] + section[role="region"] > header:first-child > button,
    section[data-type="sidebar"] + section[role="region"] > header:first-child > a {
      background-position: 3.5rem center;
    }

    /* Switches */
    #switches div:last-child label:last-child {
      margin-left: 2rem;
    }
    #switches div:last-child {
      margin-left: 2rem;
    }

    /* Scrolling */
    nav[data-type="scrollbar"] {
      padding-top: 1rem;
    }
    nav[data-type="scrollbar"] p {
      opacity: 1;
    }

    /* Seek bars */
    div[role="slider"] > label.icon {
      background: no-repeat right top;
      background-size: 3rem auto;
    }

    /* Tabs */
    #tabs .content {
      padding: 0;
    }
    #tabs .content .content {
      padding: 1.5rem 3rem;
    }
    #panel1 a {
      background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAABaCAYAAACv+ebYAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoTWFjaW50b3NoKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDpDM0IyRDEwMTRCQ0ExMUUzOEI3MkZFOEM1MTY1MUU0NSIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDpDM0IyRDEwMjRCQ0ExMUUzOEI3MkZFOEM1MTY1MUU0NSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOkMzQjJEMEZGNEJDQTExRTM4QjcyRkU4QzUxNjUxRTQ1IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOkMzQjJEMTAwNEJDQTExRTM4QjcyRkU4QzUxNjUxRTQ1Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+RALCTwAAAgdJREFUeNrsmLFKA0EQhnNeULhOwSIQrAQFraI2FkKsBLGSdIakClj4BNa+gQhWSayUlBYWKQSLYEBtYsRCEAKKYKGgiKQ5Z2ACR7jd7N5NFGQGfoJzs/vlbjdz6+/4vp/4ixhJ/FEIWMACZotkWNJxHO2gQqHgwcc0/fkA+tLVVyoVMzBMrJpjHLSHJSCPcgitgnZBb7HuWBGToAvQbF8ev8A2KAtaAb1yr/F+CDQYeO2Ae3OlQTmDuk2qZQMv454zqHOolg3sWSyJxwluWYBbnOAbUNugrk21bGA8ppRAXU1Nl2p87p9TA7QG6oRc69C1xjAaCMY5aAa0AZqn3C3oFPQdu1cPCATUSEN9OyVp7XC35jV1W3T3JZMbGgSeAjVBh/Roywo4QvEVNEe1TRobCYyt7xKUCeTcEHieoG4gl6Gx6ShrfARKheR78F6U+6C9SNEcqzbgRXrNqSIIdzV1WZrryvRRLxlsOncAVDuXCjzGeLwatQF/MII/bcBPjOBnG/AdI/jeBoxN/4UBinM82jaQOgO4HqVzHTOAT6J0rjPQREzwu/JUKB6IgAUsYAELWMAC/vUIPfoUi0XtoOr6jpWJ6ucWzI4+Tu1aNUckEzUMLCaqzh0QEzVWrZioyh6QEBNVTFQxUcVENXzUYqLGCTFRtWAxUaOGmKjyb6qABfyPwD8CDADIZJaymr3BjwAAAABJRU5ErkJggg==);
    }
    #panel2 a {
      background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAABaCAYAAACv+ebYAAAAGXRFWHRTb2Z0d2FyZQBBZG9iZSBJbWFnZVJlYWR5ccllPAAAAyRpVFh0WE1MOmNvbS5hZG9iZS54bXAAAAAAADw/eHBhY2tldCBiZWdpbj0i77u/IiBpZD0iVzVNME1wQ2VoaUh6cmVTek5UY3prYzlkIj8+IDx4OnhtcG1ldGEgeG1sbnM6eD0iYWRvYmU6bnM6bWV0YS8iIHg6eG1wdGs9IkFkb2JlIFhNUCBDb3JlIDUuMy1jMDExIDY2LjE0NTY2MSwgMjAxMi8wMi8wNi0xNDo1NjoyNyAgICAgICAgIj4gPHJkZjpSREYgeG1sbnM6cmRmPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5LzAyLzIyLXJkZi1zeW50YXgtbnMjIj4gPHJkZjpEZXNjcmlwdGlvbiByZGY6YWJvdXQ9IiIgeG1sbnM6eG1wPSJodHRwOi8vbnMuYWRvYmUuY29tL3hhcC8xLjAvIiB4bWxuczp4bXBNTT0iaHR0cDovL25zLmFkb2JlLmNvbS94YXAvMS4wL21tLyIgeG1sbnM6c3RSZWY9Imh0dHA6Ly9ucy5hZG9iZS5jb20veGFwLzEuMC9zVHlwZS9SZXNvdXJjZVJlZiMiIHhtcDpDcmVhdG9yVG9vbD0iQWRvYmUgUGhvdG9zaG9wIENTNiAoTWFjaW50b3NoKSIgeG1wTU06SW5zdGFuY2VJRD0ieG1wLmlpZDo5NjZEOTM1MTRCQ0UxMUUzOEI3MkZFOEM1MTY1MUU0NSIgeG1wTU06RG9jdW1lbnRJRD0ieG1wLmRpZDo5NjZEOTM1MjRCQ0UxMUUzOEI3MkZFOEM1MTY1MUU0NSI+IDx4bXBNTTpEZXJpdmVkRnJvbSBzdFJlZjppbnN0YW5jZUlEPSJ4bXAuaWlkOjlEMUREOTkyNEJDQjExRTM4QjcyRkU4QzUxNjUxRTQ1IiBzdFJlZjpkb2N1bWVudElEPSJ4bXAuZGlkOjk2NkQ5MzUwNEJDRTExRTM4QjcyRkU4QzUxNjUxRTQ1Ii8+IDwvcmRmOkRlc2NyaXB0aW9uPiA8L3JkZjpSREY+IDwveDp4bXBtZXRhPiA8P3hwYWNrZXQgZW5kPSJyIj8+XJ6+IQAAA0pJREFUeNrsmF1IVEEUx+fqwmoRRS9FREUf9lSs9lBERNSjRBB9PAQtGZWla+ZDH/QQ9VAQ9GVqIaH1YFRgRAW99BAUBVFa9BBREhm9FIVGUQm5/Q+eibvj3L0zd3e1jznwY53r7Px35p6Ze+7fS6fTYjSiSIxSOGEn7IT/fuGY53k5DZBMJuP42AjOAeNjMIYv0mcpOAJ2gwFL7RLQCirADlNxudQnwU7QBqIuQTVoMf1+DCwFW7i9AbwF+wL6HwArlWvFivggqDERPqH8yr2gF5zR9C8HC0LGpOU+Ct6ECc/WXD8NPoMO5Xo3mKqZccLXbgwTlcK3wWrNYG08wD3f9YOMP8aDPv67CdSbJldHwP8ou99ZJFczqLPJ6psBAofAa4MxvoMqkFJEPU7aeJDwACeDGg8MZ/oDtGtEW3h/l2Tbx62a2ZVH3M9StDosueRypXjZZdTxfRu0FG5WRO+An0qfG8WJxO+d8BLMAfO5PRF8BA8tRKdpknUymKLQrz6dapU9SOf3XAvhXt7H/ngCHit0x5ROfbyn74IxYCwv/2LwwVC8nnOnltvLaIYmz+MusNmXpXSy3QKTDIXTnB9NUQqBS/y0kkHn831QZilexYlrVYHQeb3f154JHoFtmu9RUi3UiLfzPrcufQ4rx+A4cBY853u4iO/pM942a4wrEIM+NPP3/OtL+VoZX1fjMmgAp/JV7NGAS3i7hI1H1cyxsErEpsrs4sPlokHfBk7QeL7K234uj1aBnpC+68DxfNfV18E8sCvLM/s82FOIgv4b389ZnM1XwSfwFCwHm8CXXLLa5HncyRTx1gutQjzngThhJ+yEnbATdsJO+P8R9tjLjBwXKlORTFRPXKH3sJxMVOlz0TuVM1GzLjV5EhXKdVoynYl6jV9fwmKG+FNNVJpxpxhuogpOshUi00TNllyCfQ8jP9OZqP74N01UeXJRVIpME/WVGLITbU1UEt3ua5OlONxEFWu3ysaImqj+GVNM4F84ndtfxZCr98JCvJETLeuMVWHBp5g0UeWS25ioHh9ANb7JjJiJmhLORLUsfQpmouqSSxfrRaaJGhS0552Jmpfy1pmokU1U06y2eTNxJqoTdsJOuLDxS4ABAHTFDfqZk4Q5AAAAAElFTkSuQmCC);
    }
    #panel3 a {
      background-image: url(data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAB4AAABQCAYAAAAOYsW+AAAA30lEQVRo3u2YMQrCQBBFv95ICXqTtDmJKfQk01olED2DiugF1MImjXqBWNisEIZVFIKFvFftzmdnt3kwrAQAAB/S84Usy0aS1q68N7NhyKeSJi6fmVke8p2kgcvHZrZpF/qRx1witesX+e2TnrGLz5IaVzu9WD85ttYHlzWh5/uLzewuqXQHF639SlLd2teh9mTpHl6GnoDHePxbjwt3sPrC48o9vMBjPMZjPAYAAACAjuZqzbfRuVppMgx5dK5WmuQhj87VShPmakeadP7PFXoCHuMxHgMe4zEeA8C/8ABPaein4jLEkQAAAABJRU5ErkJggg==);
    }

    /* Filters */
    [role="tablist"][data-type="filter"] {
      margin-bottom: 2rem;
    }

    /* Device rotation */
    .landscape section[role="region"]#drawer > header:first-child {
      /* Whatever needs to be changed on landscape */
    }
  </style>
</head>
<body>
  <section id="index" data-position="current">
    <section data-type="sidebar">
      <header>
        <menu type="toolbar">
          <a href="#">Retour</a>
        </menu>
        <h1>A propos</h1>
      </header>
      <article class="content header">
      <section data-type="list">
        <header>Développeur</header>
        <ul>
          <li>
            <aside class="pack-end">
              <img alt="photo" src="images/photo.jpg">
            </aside>
              <p style="color: #E7E7E7;">Philippe Joulot</p>
              <p>02/06/1991 - FRANCE</p>
          </li>
        </ul>
        <header>Green Arrow France</header>
        <ul>
		  <li>
		  <p style="color: #E7E7E7;">Informations</p>
		  <p>Site sur la série télé Arrow</p>
		  </li>
          <li>
		  <a id="btn-website-a-propos" href="#">
		  <p style="color: #E7E7E7;">URL</p>
		  <p>www.green-arrow-france.fr</p>
		  </a>
		  </li>
		</ul>
		<header>Remerciements</header>
		<ul>
          <li>
		  <p style="color: #E7E7E7;">Staff du site</p>
		  </li>
		  <li>
		  <p style="color: #E7E7E7;">Mozilla</p>
		  </li>
		</ul>
      </section>
    </article>
    </section>

    <section id="drawer" role="region">
      <header class="fixed">
        <a href="#"><span class="icon icon-menu">hide sidebar</span></a>
        <a href="#drawer"><span class="icon icon-menu">show sidebar</span></a>
        <h1>Green Arrow France</h1>
      </header>
      <article class="scrollable header">
        <div data-type="list">
          <ul>
			<li>
              <a id="btn-website" href="#">
                <p style="">Visitez Green Arrow France</p>
              </a>
            </li>
			<?php echo $listeNews; ?>
          </ul>
        </div>
      </article>
    </section> <!-- end drawer -->
  </section> <!-- end index -->
  
<?php  echo $sectionsNews; ?>


  <script type="text/javascript" defer src="js/status.js"></script>
  <script type="text/javascript" defer src="js/seekbars.js"></script>
  <script type="text/javascript">
    var isFFOS = ("mozApps" in navigator && navigator.userAgent.search("Mobile") != -1);
  
    document.querySelector('#btn-website').addEventListener ('click', function () {

	if ( isFFOS ) {
		var openURL = new MozActivity({
		name: "view",
		data: {
			type: "url",
			url: "http://www.green-arrow-france.fr"
		}
		});
    } else {
      window.location.href = "http://www.green-arrow-france.fr"
    }
	
	});
	
	document.querySelector('#btn-website-a-propos').addEventListener ('click', function () {

		if ( isFFOS ) {
			var openURL = new MozActivity({
			name: "view",
			data: {
				type: "url",
				url: "http://www.green-arrow-france.fr"
			}
			});
		} else {
		  window.location.href = "http://www.green-arrow-france.fr"
		}
	
	});
	
	var classname = document.getElementsByClassName("lien-navigateur");

    var openLink = function() {
        if ( isFFOS ) {
			var openURL = new MozActivity({
			name: "view",
			data: {
				type: "url",
				url: this.title
			}
			});
		} else {
		  window.location.href = this.title
		}
    };

    for(var i=0;i<classname.length;i++){
        classname[i].addEventListener('click', openLink, false);
    }
	<?php echo $javascriptNews; ?>
  </script>

</body>
</html>
