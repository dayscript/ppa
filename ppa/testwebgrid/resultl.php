							    <div class="espacio" id="canales">
								<? 
								$REQUEST = array_merge($_POST, $_GET);
										$sql = "SELECT channel.* " .
										"FROM channel, client_channel " .
										"WHERE channel.id =  client_channel.channel AND " .
										"client_channel.client = 65 and client_channel._group like '".$REQUEST["option"]."' ORDER BY channel.name";										
								$result = db_query( $sql );
								?>
								<select name="id_canales" id="id_canales" >
							      <option value="0">-- Elija uno --</option>
								<?
									while( $row = db_fetch_array( $result ) )
									{
									print_r($row);
								?>
										<option value="<?=$row['id']?>"><?=ucfirst( $row['name'] )?></option>
								<?	
								}
								?>								  
						        </select></div>
									 

