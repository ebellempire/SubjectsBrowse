<?php
function sb_get_subjects(){
	$subjects = [];
	if($records = get_records('ElementText',array('element_id'=>'49'),'all')){
		foreach($records as $r){
			$TEXT=trim($r->text);
			$subjects[] = array(
				'text' => $TEXT,
				'letter' => strtolower(substr($r->text, 0, 1)),
				'count' => count(array_filter($records, function ($element) use($TEXT){ 
					return $element['text'] == $TEXT; 
				})), // @todo
			);
		}
		$subjects = array_unique($subjects, SORT_REGULAR); // unique
	}
	return $subjects;
}

function sb_subjects_list($subjects){
	if($subjects){
		echo '<ul>';
		foreach($subjects as $subject){
			$link = WEB_ROOT;
			$link .= htmlentities('/items/browse?term=');
			$link .= rawurlencode($subject['text']);
			$link .= htmlentities('&search=&advanced[0][element_id]=49&advanced[0][type]=contains&advanced[0][terms]=');
			$link .= urlencode(str_replace('&amp;', '&', $subject['text']));
			echo '<li data-letter="'.$subject['letter'].'" data-count="'.$subject['count'].'"><a href="'.$link.'">'.$subject['text'].' <span class="count">'.$subject['count'].'</span></a></li>';
		}
		echo '</ul>';
	}
}

function sb_ascend($a, $b){
	return $a['count'] - $b['count'];
}
function sb_descend($a, $b){
	return $b['count'] - $a['count'];
}