<?php



//
// Convert Wikilike links
//
function do_wikilinks( $text )
{
	global $db;
	
	$livres_ID = array( 1 => 2857,
			    2 => 3156, 
			    3 => 2858, 
			    4 => 2859, 
			    5 => 3162, 
			    6 => 2860 
			   );
	
	$tab = explode( '|', $text );
	$data = array();
	
	$post_title = str_replace( "'", '’', pun_trim( $tab[0] ) );
	$link_title = str_replace( "'", '’', pun_trim( count( $tab ) > 1 ? $tab[1] : $tab[0] ) );
	$link = $link_title;
	
	$link_query = "SELECT ID, guid, post_parent FROM wp_posts WHERE post_title='".$db->escape( $post_title )."'  AND post_type='page' AND post_status='publish' AND post_parent IN (".implode( ', ', $livres_ID ).")";
	$link_sql = $db->query( $link_query, false ) or error( 'Can\'t get page list!', __FILE__, __LINE__, $db->error() );
	
	while ( $data = $db->fetch_assoc( $link_sql ) ) :
		if ( count( $data ) > 0 && $data['guid'] != '' ) :
			$link = '<a href="'.$data['guid'].'">';
			if ( in_array( $data['post_parent'], $livres_ID ) ) :
				$link .= '<em>'.$link_title.'</em>';
			else :
				echo '<!-- post_parent = '.$data['post_parent'].' -->';
				$link .= $link_title;
			endif;
			$link .= '</a>';
		endif;
	endwhile;
	
	return $link;
}


//
// Parse the contents of [livre] bbcode
//
function handle_livre_tag( $livre, $episodes = '' )
{
	global $db;
	
	$livres_ID = array( 1 => 2857,
			    2 => 3156, 
			    3 => 2858, 
			    4 => 2859, 
			    5 => 3162, 
			    6 => 2860 
			   );
	
	$data = array();
	$ret = '';

	if ( $livre != '' && $episodes != '' ) :
		$livre = (int) $livre;
		$episodes = array_map( create_function( '$e', 'return (int)$e;' ), explode( ",", $episodes ) );
		
		if ( $livre >= 1 && $livre <= 5 ) :
			
			if ( count( $episodes ) > 0 ) :
				$query = "SELECT post_title, guid, menu_order FROM wp_posts WHERE post_type='page' AND post_status='publish' AND post_parent='".$livres_ID[$livre]."' AND menu_order IN (".implode( ",", $episodes ).")";
			//elseif ( count( $episodes ) == 0 ) :
			//	$query = "SELECT post_title, guid FROM wp_posts WHERE post_type='page' AND post_status='publish' AND post_parent='".$livres_ID[$livre]."' ORDER BY menu_order";
			endif;
		endif;
		
		$result = $db->query( $query, false ) or error( 'Can\'t get episode list!<br />', __FILE__, __LINE__, $db->error() );
	
		if ( $db->num_rows( $result ) > 0 ) :
			
			while ( $r = $db->fetch_assoc( $result ) ) :
				$data[$r['menu_order']] = '<li><a href="'.$r['guid'].'"><em>'.$r['post_title'].'</em></a></li>';
			endwhile;
			
		endif;
		
		if ( count( $data ) > 0 ) :
			
			$ret .= '<ul>';
			foreach( $episodes as $episode ) :
				$ret .= $data[$episode];
			endforeach;
			$ret .= '</ul>';
			
		endif;
		
	endif;

	return $ret;
}








