<?

class htmlParser {
	
	var $arSitio;
	var $iIndice;
	var $claInicialOK;
	var $claFinalOK;
	var $seleccion;
	
	function htmlParser ($url = '', $inicio = '', $final= '') {
		$this->arSitio = array();
		$tmp = "";
		if($this->arSitio = file( $url )){
			foreach($this->arSitio as $linea){
				$tmp .= trim(eregi_replace("<t", "<separador><t", eregi_replace("</", "<separador></", $linea))) ." ";
//				$tmp .= trim(eregi_replace("</tr>", "</tr><separador>", eregi_replace("</td>", "</td><separador>", $linea)));
			}

		}
		$this->arSitio = explode("<separador>", $tmp);
		unset($tmp);
	}

		
	function seleccion($claveInicial, $claveFinal = '', $iIndice = 0, $opc = false) {
		$this->seleccion = array();
		$this->nLineas = count ($this->arSitio);
		$this->iIndice = $iIndice;
		for( ; $this->iIndice    < $this->nLineas && ( !ereg( $claveInicial, unhtmlentities( $this->arSitio[$this->iIndice]) ) ) ; $this->iIndice++ ){
			;
		}
		$this->claInicialOK = ( $this->iIndice >= $this->nLineas ? false : true );
		
		if ($opc){
 			$this->seleccion[] = $this->arSitio[$this->iIndice];
 			$this->iIndice++;
		}
		
		if( $claveFinal == "" )
		{
			for($i=0 ; $this->iIndice < $this->nLineas; $this->iIndice++ ){
	 			$this->seleccion[] = $this->arSitio[$this->iIndice];
	 	 	}
	 	}
		else
		{
			for($i=0 ; $this->iIndice < $this->nLineas && ( !ereg( $claveFinal, unhtmlentities( $this->arSitio[$this->iIndice]) ) ); $this->iIndice++ ){
	 			$this->seleccion[] = $this->arSitio[$this->iIndice];
	 	 	}
	 	}
		
		$this->claFinalOK = ( $this->iIndice >= $this->nLineas ? false : true );
		
		if ( ( !$this->claInicialOK || !$this->claFinalOK ) && $final != ''){
			echo "<br>";
			echo ($this->claInicialOK ? "No se encuentra la clave Final: <i>". htmlentities($claveFinal) ."</i><br>" : "No se encuentra la clave Inicial: <i>". htmlentities($claveInicial) ."</i><br>");
			$this->seleccion = array();
//			exit;
		}
 	 	
 	 	if (empty($this->seleccion)){
 	 		return false;
 	 	} else {
	 	 	return $this->seleccion;
	 	}
	 	
	}
	
	function unir ($iUnir, $titulo = false) {
		if($titulo){
			for($i=1,$j=1;$i<=count($this->seleccion)/$iUnir;$i++){
				$tmp = array();
				for(;$j<=$iUnir*$i;$j++){
					$tmp[]=$this->seleccion[$j];
				}
				$tmpSeleccion[] = $tmp;
			}
		} else {
			for($i=1,$j=0;$i<=count($this->seleccion)/$iUnir;$i++){
				$tmp = array();
				for(;$j<$iUnir*$i;$j++){
					$tmp[]=$this->seleccion[$j];
				}
				$tmpSeleccion[] = $tmp;
			}
		}

		$this->seleccion = $tmpSeleccion;
		unset($tmp);
		unset($tmpSeleccion);
	} 
	
	function separar ($delimitador, $iLinea){
		$arTmp = explode($delimitador, $this->seleccion[$iLinea]);
//		print_r($arTmp);
		$primera = array_slice($this->seleccion, 0, $iLinea);
		$segunda = array_slice($this->seleccion, $iLinea+1);
		$this->seleccion = array_merge($primera,$segunda,$arTmp);
		unset($arTmp);
		unset($primera);
		unset($segunda);
	}
	
	function tomaLineas ($arLineas, $titulo = false){
		$tmpSeleccion = array();
		if($titulo){
			foreach($arLineas as $linea){
				$tmpSeleccion[] = $this->seleccion[$linea+1];
			}
		} else {
			foreach($arLineas as $linea){
				$tmpSeleccion[] = $this->seleccion[$linea];
			}
		}		
		$this->seleccion = $tmpSeleccion;
		unset($tmpSeleccion);
	}

	function arStripTags ($tags = false) {
		$tmpSeleccion = array();
		foreach($this->seleccion as $select){
			if (($i=trim(strip_tags(html_entity_decode($select), $tags)))!="")
				$tmpSeleccion[] = $i;
		}
		$this->seleccion = $tmpSeleccion;
		unset($tmpSeleccion);
	}
	
	function echoSeleccion () {
//	print_r($this->seleccion);
//	echo "<br>";
		$j=0;
		foreach($this->seleccion as $select){
			echo "[$j]=><br>";
			$i=0;
			if(is_array($select)){
				foreach($select as $sel){
					echo "&nbsp;&nbsp;&nbsp;&nbsp;[$i]=> ";
					echo htmlentities($sel);
					echo "<br>";
					$i++;
				}
			} else {
				echo "&nbsp;&nbsp;&nbsp;&nbsp; ";
				echo htmlentities($select);
			}
				
			echo "<br>";
			$j++;
		}

	}

}

function unhtmlentities ($string){
	$trans_tbl = get_html_translation_table (HTML_ENTITIES);
	$trans_tbl = array_flip ($trans_tbl);
	return strtr(ereg_replace("&nbsp;", " ", $string), $trans_tbl);
}
?>