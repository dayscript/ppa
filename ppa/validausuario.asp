<%
Response.Expires = -1441
Response.Expiresabsolute = Now() - 1
Response.AddHeader "pragma","no-cache"
Response.AddHeader "cache-control","private"
Response.CacheControl = "no-cache"

Session.Timeout=60
USUARIO=SESSION("IUSUARIO") 
GRUPO=SESSION("IGRUPO") 
dim permisogrupo
dim permisopagina
permisopagina=split(PERMISO,",", -1, 1)
permisogrupo=split(GRUPO,",", -1, 1)
sw=0
For I=0 to Ubound(permisopagina)
	For J=0 to Ubound(permisogrupo)
		if trim(cstr(permisopagina(I)))=trim(cstr(permisogrupo(J))) then
			sw=1
		end if		
	next 
Next
if sw=0 then 
	response.Redirect("denegado2.asp")	
end if 
%>