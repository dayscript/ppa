<? 
function synopsisView( $view, $result ){ 
	if( $view == "search" ){?>
<div>
	<?=$result?>
<form>
  <input name="search" type="text" /><br />
  <input value="Buscar" type="submit" />
</form>
</div>
<?}
}
synopsisView( "search", $sresult );
print_r( $_POST );
echo "<br>";
print_r( $_GET );
?>
