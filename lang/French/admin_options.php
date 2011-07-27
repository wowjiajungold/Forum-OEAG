<?php

// Language definitions used in admin-options.php
$lang_admin_options = array(

'Bad HTTP Referer message'			=>	'Mauvais HTTP_REFERER. Si vous avez déplacé ces forums d\'un endroit à un autre ou si vous avez changé de domaine, vous devez mettre à jour l\'URL de base manuellement dans la base de données (recherchez o_base_url dans la table config) et nettoyer le cache en supprimant tous les fichiers *.php dans le répertoire /cache.',
'Must enter title message'			=>	'Vous devez spécifier un nom pour le forum.',
'Invalid e-mail message'			=>	'L\'adresse électronique d\'administrateur que vous avez indiquée est invalide.',
'Invalid webmaster e-mail message'	=>	'L\'adresse électronique de Webmestre que vous avez indiquée est invalide.',
'SMTP passwords did not match'		=>	'Vous devez saisir le mot de passe SMTP deux fois à l\'identique pour le modifier.',
'Enter announcement here'			=>	'Veuillez saisir votre annonce ici.',
'Enter rules here'					=>	'Veuillez saisir vos règles ici.',
'Default maintenance message'		=>	'Les forums sont temporairement fermés pour maintenance. Veuillez essayer à nouveau dans quelques minutes.',
'Timeout error message'				=>	'La valeur pour «&#160;Délai avant déconnexion&#160;» doit être inférieure à celle du champ «&#160;Délai de fin de visite&#160;».',
'Options updated redirect'			=>	'Options mises à jour. Redirection&#160;…',
'Options head'						=>	'Options',

// Essentials section
'Essentials subhead'			=>	'Principal',
'Board title label'				=>	'Titre du forum',
'Board title help'				=>	'Le titre de ce forum (affiché en haut de chaque page). Ce champ <strong>ne</strong> doit <strong>pas</strong> contenir de code HTML.',
'Board desc label'				=>	'Description du forum',
'Board desc help'				=>	'Une courte description de ce forum (affichée en haut de chaque page). Ce champ peut contenir du code HTML.',
'Base URL label'				=>	'URL de base',
'Base URL help'					=>	'L\'URL complète du forum sans barre oblique finale (ex. : http://www.mondomaine.com/forums). Ceci <strong>doit</strong> être correct pour que les fonctionnalités dédiées aux administrateurs et aux modérateurs puissent être conservées. Si vous obtenez des erreurs «&#160;Bad referer&#160;», elle est sûrement incorrecte.',
'Timezone label'				=>	'Fuseau horaire par défaut',
'Timezone help'					=>	'Le fuseau horaire par défaut pour les invités et les utilisateurs qui s\'inscrivent sur le forum.',
'DST label'						=>	'Ajustement pour l\'heure d\'été',
'DST help'						=>	'Pour activer l\'heure d\'été (avance d\'une heure).',
'Language label'				=>	'Langue par défaut',
'Language help'					=>	'Ceci est la langue par défaut utilisée si le visiteur est un invité ou si un utilisateur n\'a pas modifié ce paramètre dans son pofil. Si vous supprimez un paquetage de langue, ceci devra être mis à jour.',
'Default style label'			=>	'Style par défaut',
'Default style help'			=>	'Ceci est le style par défaut utilisé si le visiteur est un invité ou si un utilisateur n\'a pas modifié ce paramètre dans son profil.',

// Essentials section timezone options
'UTC-12:00'					=>	'(UTC-12:00) Ligne de changement de date OUEST',
'UTC-11:00'					=>	'(UTC-11:00) Niue, Samoa',
'UTC-10:00'					=>	'(UTC-10:00) Hawaii et des îles Aléoutiennes, Îles Cook',
'UTC-09:30'					=>	'(UTC-09:30) Îles Marquises',
'UTC-09:00'					=>	'(UTC-09:00) Alaska, Îles Gambier',
'UTC-08:30'					=>	'(UTC-08:30) Îles Pitcairn',
'UTC-08:00'					=>	'(UTC-08:00) Pacifique',
'UTC-07:00'					=>	'(UTC-07:00) Montagnes, les Rocheuses',
'UTC-06:00'					=>	'(UTC-06:00) Centre',
'UTC-05:00'					=>	'(UTC-05:00) Est',
'UTC-04:00'					=>	'(UTC-04:00) Atlantique',
'UTC-03:30'					=>	'(UTC-03:30) Terre-Neuve',
'UTC-03:00'					=>	'(UTC-03:00) Amazone, Centre du Groenland',
'UTC-02:00'					=>	'(UTC-02:00) Milieu de l\'Atlantique',
'UTC-01:00'					=>	'(UTC-01:00) Açores, Cap Vert, Groenland de l\'Est',
'UTC'						=>	'(UTC) Europe Occidentale, Greenwich',
'UTC+01:00'					=>	'(UTC+01:00) Europe Centrale, Afrique Occidentale',
'UTC+02:00'					=>	'(UTC+02:00) Europe de l\'Est, Afrique Centrale',
'UTC+03:00'					=>	'(UTC+03:00) Moscou, Afrique Orientale',
'UTC+03:30'					=>	'(UTC+03:30) Iran',
'UTC+04:00'					=>	'(UTC+04:00) Golf, Samara',
'UTC+04:30'					=>	'(UTC+04:30) Afghanistan',
'UTC+05:00'					=>	'(UTC+05:00) Pakistan, Iekaterinbourg',
'UTC+05:30'					=>	'(UTC+05:30) Inde, Sri Lanka',
'UTC+05:45'					=>	'(UTC+05:45) Népal',
'UTC+06:00'					=>	'(UTC+06:00) Bangladesh, Bhutan, Novosibirsk',
'UTC+06:30'					=>	'(UTC+06:30) Îles Cocos, Myanmar',
'UTC+07:00'					=>	'(UTC+07:00) Indochine, Krasnoyarsk',
'UTC+08:00'					=>	'(UTC+08:00) Grande Chin, Australie occidentale, Irkutsk',
'UTC+08:45'					=>	'(UTC+08:45) Australie occidentale',
'UTC+09:00'					=>	'(UTC+09:00) Japon, Corée, Chine',
'UTC+09:30'					=>	'(UTC+09:30) Australie Méridionale',
'UTC+10:00'					=>	'(UTC+10:00) Australie Orientale, Vladivostok',
'UTC+10:30'					=>	'(UTC+10:30) Île Lord Howe',
'UTC+11:00'					=>	'(UTC+11:00) Île Salomon, Magadan',
'UTC+11:30'					=>	'(UTC+11:30) Île Norfolk',
'UTC+12:00'					=>	'(UTC+12:00) Nouvelle Zélande, Fiji, Kamchatka',
'UTC+12:45'					=>	'(UTC+12:45) Îles Chatham',
'UTC+13:00'					=>	'(UTC+13:00) Tonga, Îles Phoenix',
'UTC+14:00'					=>	'(UTC+14:00) Îles de la Ligne',

// Timeout Section
'Timeouts subhead'			=>	'Horaire et délais avant interruption',
'Time format label'			=>	'Format de l\'horaire',
'PHP manual'				=>	'manuel PHP',
'Time format help'			=>	'[Format actuel&#160;: %s]. Rendez-vous au %s pour des options de formatage.',
'Date format label'			=>	'Format de la date',
'Date format help'			=>	'[Format actuel&#160;: %s]. Rendez-vous au %s pour des options de formatage.',
'Visit timeout label'		=>	'Délai d\'interruption de visite',
'Visit timeout help'		=>	'Délai en secondes d\'inactivité d\'un utilisateur avant de mettre à jour ses données de dernière visite (touche principalement les indicateurs de nouveau message).',
'Online timeout label'		=>	'Délai de déconnexion',
'Online timeout help'		=>	'Délai en secondes d\'inactivité d\'un utilisateur avant d\'être retiré de la liste des utilisateurs en ligne.',
'Redirect time label'		=>	'Délai de redirection',
'Redirect time help'		=>	'Délai en secondes avant redirection. Si défini à 0, la page de redirection ne sera pas affichée (non recommandé).',

// Display Section
'Display subhead'				=>	'Affichage',
'Version number label'			=>	'Numéro de version',
'Version number help'			=>	'Afficher la version de FluxBB dans le pied de page.',
'Info in posts label'			=>	'Infos sur l\'utilisateur dans les messages',
'Info in posts help'			=>	'Afficher les informations à propos de l\'utilisateur ayant créé le  message sous son nom d\'utilisateur dans la discussion. Les informations données sont&#160;: le lieu, la date d\'inscription, le nombre de messages publiés et les liens de contact (e-mail et URL).',
'Post count label'				=>	'Nombre de messages',
'Post count help'				=>	'Affiche le nombre de messages publiés par un utilisateur (indiqué dans les discussions, le profil et la liste d\'utilisateurs).',
'Last visit label'              =>  'Date de la dernière visite',
'Last visit help'               =>  'Affiche la date de la dernière connexion des utilisateurs au forum.',
'Smilies label'					=>	'Émoticônes dans les messages',
'Smilies help'					=>	'Convertit les émoticônes en petites icônes graphiques.',
'Smilies sigs label'			=>	'Émoticônes dans les signatures',
'Smilies sigs help'				=>	'Convertit les émoticônes en petites icônes graphiques dans les signatures d\'utilisateurs.',
'Clickable links label'			=>	'Rendre les liens cliquables',
'Clickable links help'			=>	'Si activée, FluxBB détectera automatiquement les URL dans les messages et les convertira en hyperliens cliquables.',
'Topic review label'			=>	'Résumé de discussion',
'Topic review help'				=>	'Nombre maximal de sujets à afficher lors de la composition de message (le plus récent en premier). 0 pour désactiver.',
'Topics per page label'			=>	'Discussions par page',
'Topics per page help'			=>	'Le nombre de discussions à afficher par défaut par page dans un forum. Les utilisateurs peuvent personnaliser ce paramètre.',
'Posts per page label'			=>	'Messages par page',
'Posts per page help'			=>	'Le nombre de messages à afficher par défaut par page dans une discussion. Les utilisateurs peuvent personnaliser ce paramètre.',
'Indent label'					=>	'Tabulation',
'Indent help'					=>	'Si définie à 8, une indentation normale sera utilisée lors de l\'affichage du texte contenu dans une balise [code][/code]. Sinon, le nombre d\'espaces indiqué sera utilisé pour l\'indentation du texte.',
'Quote depth label'				=>	'Niveau maximum de citations imbriquées',
'Quote depth help'				=>	'Le nombre de fois qu\'une balise [quote] peut apparaîre à l\'intérieure d\'une autre balise [quote], toute balise de niveau supérieur sera ignorée.',

// Features section
'Features subhead'				=>	'Fonctionnalités',
'Quick post label'				=>	'Réponse rapide',
'Quick post help'				=>	'Si activée, FluxBB ajoutera un formulaire de réponse rapide au bas des sujets. Ainsi, les utilisateurs pourront répondre directement à partir du sujet.',
'Users online label'			=>	'Utilisateurs en ligne',
'Users online help'				=>	'Affiche des informations sur la page d\'accueil indiquant le nombre d\'invités et d\'inscrits visitant actuellement les forums.',
'Censor words label'			=>	'Mots censurés',
'Censor words help'				=>	'Activez ceci pour censurer des mots spécifiques dans le forum. Rendez-vous à la rubrique %s pour davantage d\'informations.',
'Signatures label'				=>	'Signatures',
'Signatures help'				=>	'Autoriser les utilisateurs à ajouter une signature en bas de message.',
'User ranks label'				=>	'Rangs des utilisateurs',
'User ranks help'				=>	'Activer cela pour utiliser les rangs d\'utilisateurs. Rendez-vous à la rubrique %s pour davantage d\'informations.',
'User has posted label'			=>	'Indication de message antérieur',
'User has posted help'			=>	'Cette fonctionnalité affiche un point devant les sujets dans viewforum.php si l\'utilisateur actuellement connecté a déjà écrit un message dans le sujet. Désactivez cette fonction si vous constatez des problèmes de surcharge du serveur.',
'Topic views label'				=>	'Affichages de sujet',
'Topic views help'				=>	'Conserver le nombre de fois qu\'un sujet a été visionné. Désactivez cette option si vous constatez une surcharge du serveur dans un forum très fréquenté.',
'Quick jump label'				=>	'Basculement rapide',
'Quick jump help'				=>	'Activer la liste déroulante de basculement rapide (basculer vers un forum).',
'GZip label'					=>	'Compression GZip',
'GZip help'						=>	'Si activée, FluxBB compressera au format gzip ce qui sera envoyé au navigateur. Cela réduira l\'utilisation de la bande passante mais utilisera davantage les ressources du processeur. Cette fonctionnalité demande une configuration de PHP avec zlib (--with-zlib). Remarque : si vous avez d\'ores et déjà configuré un des modules Apache mod_gzip ou mod_deflate pour compresser les scripts PHP, vous devriez désactiver cette fonctionnalité.',
'Search all label'				=>	'Rechercher dans tous les forums',
'Search all help'				=>	'Si désactivée, les recherches ne pourront être effectuées que dans un forum à la fois. Désactivez ceci si la charge du serveur est trop élevée à cause d\'un nombre de recherches trop important.',
'Menu items label'				=>	'Éléments de menu supplémentaires',
'Menu items help'				=>	'En ajoutant des hyperliens HTML dans cette boîte, un nombre illimité d\'éléments peuvent être ajoutés au menu de navigation situé en haut de chaque page. Le format pour ajouter de nouveaux liens est le suivant : X = &lt;a href="URL"&gt;INTITULE DU LIEN&lt;/a&gt; où X est la position à laquelle le lien devra être inséré (ex. : 0 pour insérer au début et 2 pour insérer après l\'élément «&#160;Liste d\'utilisateurs&#160;»). Chaque élément devra se trouver dans une nouvelle ligne.',

// Feeds section
'Feed subhead'					=>	'Syndication',
'Default feed label'			=>	'Type de syndication par défaut',
'Default feed help'				=>	'Sélectionner le type de syndication à utiliser. Note : ne pas sélectionner de type par défaut ne désactive pas la syndication : elle sera simplement cachée.',
'None'							=>	'Aucun',
'RSS'							=>	'RSS',
'Atom'							=>	'Atom',
'Feed TTL label'				=>	'Durée de cache',
'Feed TTL help'					=>	'Les syndications peuvent être mise en cache pour limiter les ressources consommées.',
'No cache'						=>	'Ne pas utiliser le cache',
'Minutes'						=>	'%d minutes',

// Reports section
'Reports subhead'				=>	'Signalements',
'Reporting method label'		=>	'Méthode de signalement',
'Internal'						=>	'Interne',
'By e-mail'						=>	'E-mail',
'Both'							=>	'Les deux',
'Reporting method help'			=>	'Choisissez la méthode pour traiter les signalements de discussion/message. Vous pouvez opter pour le système de signalement interne, l\'envoi d\'un e-mail à la liste de diffusion (voir ci-dessous) ou effectuer les deux opérations.',
'Mailing list label'			=>	'Liste de diffusion',
'Mailing list help'				=>	'Une liste d\'abonnés séparés par une virgule. Les personnes dans cette liste sont les destinataires des signalements.',

// Avatars section
'Avatars subhead'				=>	'Avatars',
'Use avatars label'				=>	'Utiliser des avatars',
'Use avatars help'				=>	'Si activée, les utilisateurs pourront envoyer vers le serveur un avatar qui sera affiché sous leur titre/rang.',
'Upload directory label'		=>	'Répertoire d\'envoi',
'Upload directory help'			=>	'Le répertoire vers lequel les avatars seront envoyés (relatif au répertoire racine de FluxBB). PHP doit avoir les permissions d\'écriture dans ce répertoire.',
'Max width label'				=>	'Largeur max',
'Max width help'				=>	'La largeur maximale autorisée pour les avatars en pixels.',
'Max height label'				=>	'Hauteur max',
'Max height help'				=>	'La hauteur maximale autorisée pour les avatars en pixels.',
'Max size label'				=>	'Taille max',
'Max size help'					=>	'La taille maximale autorisée pour les avatars en octets.',

// Signatures section
'Signatures subhead'			=>  'Signatures',
'Use signatures label'			=>  'Utiliser des images dans les signatures (une seule)',
'Use signatures help'			=>  'Si activée, les utilisateurs pourront insérer une image dans leur signature.',

// E-mail section
'E-mail subhead'				=>	'E-mail',
'Admin e-mail label'			=>	'E-mail de l\'administrateur',
'Admin e-mail help'				=>	'L\'adresse électronique de l\'administrateur du forum.',
'Webmaster e-mail label'		=>	'E-mail du webmaster',
'Webmaster e-mail help'			=>	'Ceci est l\'adresse indiquée comme adresse de provenance dans les e-mails envoyés par le forum.',
'Forum subscriptions label'		=>	'Forum subscriptions',
'Forum subscriptions help'		=>	'Enable users to subscribe to forums (receive email when someone creates a new topic).',
'Topic subscriptions label'		=>	'Topic subscriptions',
'Topic subscriptions help'		=>	'Enable users to subscribe to topics (receive email when someone replies).',
'SMTP address label'			=>	'Adresse du serveur SMTP',
'SMTP address help'				=>	'L\'adresse d\'un serveur SMTP externe à utiliser pour envoyer les e-mails. Vous pouvez spécifier un numéro de port personnalisé si le serveur SMTP n\'utilise pas le port par défaut 25 (exemple : mail.monhôte.com:3580). Laisser vide pour utiliser le programme d\'envoi d\'e-mail local.',
'SMTP username label'			=>	'Nom d\'utilisateur SMTP',
'SMTP username help'			=>	'Nom d\'utilisateur pour serveur SMTP. Indiquez un nom d\'utilisateur uniquement si cela est demandé par le serveur SMTP (la plupart des serveurs <strong>ne</strong> nécessitent <strong>pas</strong> d\'authentification).',
'SMTP password label'			=>	'Mot de passe SMTP',
'SMTP change password help'		=>	'Check this if you want to change or delete the currently stored password.',
'SMTP password help'			=>	'Mot de passe pour serveur SMTP. Indiquez un mot de passe uniquement si cela est demandé par le serveur SMTP (la plupart des serveurs <strong>ne</strong> nécessitent <strong>pas</strong> d\'authentification).',
'SMTP SSL label'				=>	'Chiffrage du SMTP par SSL',
'SMTP SSL help'					=>	'Chiffre la connexion vers le serveur SMTP en utilisant SSL. Ne devrait être utilisé que si votre serveur SMTP le demande et que votre version de PHP prend en charge SSL.',

// Registration Section
'Registration subhead'			=>	'Inscription',
'Allow new label'				=>	'Autoriser de nouvelles inscriptions',
'Allow new help'				=>	'Indique si ce forum accepte de nouvelles inscriptions. Ne désactiver que sous certaines conditions.',
'Verify label'					=>	'Vérifier les inscriptions',
'Verify help'					=>	'Si activée, les utilisateurs obtiendront par e-mail un mot de passe aléatoire lors de leur inscription. Ils pourront alors se connecter et modifier leur mot de passe dans leur profil s\'ils le souhaitent. Cette fonctionnalité demande également une vérification de leur adresse électronique s\'ils choisissent d\'en changer. Ceci est un moyen efficace d\'éviter l\'inscription abusive et de vérifier que les utilisateurs ont une adresse électronique correcte associée à leur profil.',
'Report new label'				=>	'Signaler les nouvelles inscriptions',
'Report new help'				=>	'Si activée, FluxBB notifiera les utilisateurs dans la liste de diffusion (voir ci-dessus) lorsqu\'un nouvel utilisateur s\'inscrit sur les forums.',
'Use rules label'				=>	'Règles du forum pour les utilisateurs',
'Use rules help'				=>	'Si activée, les utilisateurs devront accepter les règles lorsqu\'ils s\'inscriront (indiquez le texte ci-dessous). Ces règles seront toujours disponibles sous forme de lien dans le menu de navigation en haut de chaque page.',
'Rules label'					=>	'Indiquez vos règles d\'utilisation ici',
'Rules help'					=>	'Ici vous pouvez indiquer toute règle ou autre information que l\'utilisateur doit considérer et accepter avant de s\'inscrire. Si vous avez activé les règles ci-dessus vous devez saisir quelque chose ici, sinon, elles seront désactivées. Ce texte ne sera pas traité comme les messages ordinaires et peut contenir du code HTML.',
'E-mail default label'			=>	'Paramètre e-mail par défaut',
'E-mail default help'			=>	'Choisissez le paramètre de vie privée pour les nouvelles inscriptions d\'utilisateurs.',
'Display e-mail label'			=>	'Divulguer l\'adresse électronique aux autres utilisateurs.',
'Hide allow form label'			=>	'Masquer l\'adresse électronique mais autoriser l\'utilisation du formulaire d\'envoi d\'e-mail.',
'Hide both label'				=>	'Masquer l\'adresse électronique et ne pas autoriser l\'utilisation du formulaire d\'envoi d\'e-mail.',

// Announcement Section
'Announcement subhead'				=>	'Annonces',
'Display announcement label'		=>	'Afficher l\'annonce',
'Display announcement help'			=>	'Activez ceci pour afficher le message ci-dessous sur chaque page des forums.',
'Announcement message label'		=>	'Message d\'annonce',
'Announcement message help'			=>	'Ce texte ne sera pas traité comme les messages ordinaires et peut contenir du code HTML.',

// Maintenance Section
'Maintenance subhead'				=>	'Maintenance',
'Maintenance mode label'			=>	'Mode maintenance',
'Maintenance mode help'				=>	'Si activé, le forum ne sera disponible qu\'aux administrateurs. Ceci devra être utilisé si le forum doit être indisponible temporairement pour maintenance. ATTENTION&#160;! Ne vous déconnectez pas lorsque le forum est en mode maintenance. Vous ne seriez plus en mesure de vous connecter.',
'Maintenance message label'			=>	'Message de maintenance',
'Maintenance message help'			=>	'Le message qui s\'affichera pour les utilisateurs lorsque le forum sera en mode maintenance. Si laissé vide, un message par défaut sera utilisé. Ce texte ne sera pas traité comme les messages ordinaires et peut contenir du code HTML.',

// Captcha Section
'Captcha subhead'                   =>  'reCAPTCHA',
'Captcha registration label'        =>  'Vérification des inscriptions',
'Captcha registration help'         =>  'Si activé, un captcha sera demandé à l\'inscription.',
'Captcha post label'                =>  'Check posts',
'Captcha post help'                 =>  'When enabled, guests will be required to pass the reCAPTCHA to post.',
'Public key label'                  =>  'Clé publique',
'Public key help'                   =>  'Clé publique pour reCAPTCHA. Si vous n\'avez pas de clé, <a href="http://www.google.com/recaptcha">enregistrez-vous</a>.',
'Private key label'                 =>  'Clé privée',
'Private key help'                  =>  'Clé privée pour reCAPTCHA. Si vous n\'avez pas de clé, <a href="http://www.google.com/recaptcha">enregistrez-vous</a>.',

);