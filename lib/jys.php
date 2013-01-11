<?php
class JYS
{
	/* ------------------------------------------------------------------------------------------------
	 |  Notifications Functions
	 | ------------------------------------------------------------------------------------------------
	 */
	public static function notify_message($type, $content)
	{
		$alert = '';
		switch ($type) {
			case 'info':
				$alert = 'alert_info';
				break;
			case 'warning':
				$alert = 'alert_warning';
				break;
			case 'error':
				$alert = 'alert_error';
				break;
			case 'success':
			default:
				$alert = 'alert_success';
				break;
		}
		Session::flash('message', '<h4 class="'.$alert.'">'.$content.'</h4>');
	}

	/* ------------------------------------------------------------------------------------------------
	 |  String Functions
	 | ------------------------------------------------------------------------------------------------
	 */
	public static function generate_permalink($str)
	{
		if($str !== mb_convert_encoding(mb_convert_encoding($str, 'UTF-32', 'UTF-8'), 'UTF-8', 'UTF-32'))
		$str = mb_convert_encoding($str, 'UTF-8', mb_detect_encoding($str));
		$str = htmlentities($str, ENT_NOQUOTES, 'UTF-8');
		$str = preg_replace('`&([a-z]{1,2})(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig);`i', '\\1', $str);
		$str = html_entity_decode($str, ENT_NOQUOTES, 'UTF-8');
		$str = preg_replace(array('`[^a-z0-9]`i','`[-]+`'), '-', $str);
		$str = strtolower( trim($str, '-') );
		return $str;
	}

	public static function generate_permalink_tag($str)
	{
		$str = str_replace("#", "-sharp", $str);
		return generate_permalink($str);
	}

	public static function cut_message($message, $max_length = 100)
	{
		if (strlen($message) > $max_length){
			$message = substr($message, 0, $max_length);
			$pos = strrpos($message, " ");

			if($pos === false) return substr($message, 0, $max_length)."...";

			return substr($message, 0, $pos)."...";
		}
		else return $message;
	}

	public static function cut_text($message, $max_length = 500)
	{
		$message = html_entity_decode(strip_tags($message));
		if (strlen($message) > $max_length){
			$message = substr($message, 0, $max_length);
			$pos = strrpos($message, " ");

			if($pos === false) return substr($message, 0, $max_length)."...";

			return substr($message, 0, $pos)."...";
		}
		else return $message;
	}

	public static function generate_video($type, $url, $width = '596px', $height = '447px')
	{
		$link = '';
		switch ($type) {
			case 0: // Youtube
				$link = '<iframe src="http://www.youtube.com/embed/'.$url.'" width="'.$width.'" height="'.$height.'" frameborder="0" allowfullscreen></iframe>';
				break;
			case 1 : // Dailymotion
			default:
				$theme = "?theme=slayer&foreground=%23FF0000&highlight=%23FFFFFF&background=%23000000&logo=0&hideInfos=1";
				$link = '<iframe src="http://www.dailymotion.com/embed/video/'.$url.$theme.'" width="'.$width.'" height="'.$height.'" frameborder="0"></iframe>';
				break;
		}
		return $link;
	}

	/* ------------------------------------------------------------------------------------------------
	 |  Dates Functions
	 | ------------------------------------------------------------------------------------------------
	 */
	public static function print_full_date($date)
	{
		setlocale(LC_ALL, 'FRA');
		$strdate = strftime('%d', strtotime($date)).' - '.ucfirst(strftime('%B', strtotime($date))).' - '.strftime('%Y', strtotime($date));
		return utf8_encode($strdate);
	}

	public static function print_small_date($date)
	{
		return date_format(new DateTime($date), 'Y-m-d');
	}

	public static function print_date_ago($timestamp)
	{
		$difference = time() - $timestamp;
		$periods = array("seconde", "minute", "heure", "jour", "semaine", "mois", "année", "décennie");
		$lengths = array("60","60","24","7","4.35","12","10");
		for($j = 0; $difference >= $lengths[$j]; $j++)
		$difference /= $lengths[$j];
		$difference = round($difference);
		if($difference != 1) $periods[$j].= "s";
		$text = "Il y a $difference $periods[$j]";
		return $text;
	}

	public static function arabic_date($date)
	{
		$time		= date('H:i', strtotime($date));
		$year		= date('Y', strtotime($date));
		$num_day	= date('d', strtotime($date));
		$month		= Lang::line('date.'.date('m', strtotime($date)))->get();
		$day 		= Lang::line('date.'.strtolower(date('l', strtotime($date))))->get();
		return $day.' '.$num_day.' '.$month.' '.$year.' على الساعة '.$time.' GMT';

	}
}