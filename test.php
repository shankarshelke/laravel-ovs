<p align="center">
<form name="jump" class="center">
<select name="menu" onchange="gotoPage(this)">
<option value="#">Select an option</option>
<option value="/link1.shtml">Link 1</option>
<option value="/link2.shtml">Link 2</option>
<option value="/link3.shtml">Link 3</option>
</select>
<input type="button" onClick="location=document.jump.menu.options[document.jump.menu.selectedIndex].value;" value="GO">
</form>
</p>

<script type="text/javascript">
function gotoPage(select){
    window.location = select.value;
}
</script>