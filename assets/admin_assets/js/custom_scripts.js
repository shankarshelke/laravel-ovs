function chk_all(field)
{
    if($(field).prop('checked') == true){
        $('input[type="checkbox"]').prop('checked',true);
    }
    else
    {
        $('input[type="checkbox"]').prop('checked',false);
    }

}