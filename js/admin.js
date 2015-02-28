function tm_validateForm()
{
  var x=document.forms['emailForm']['email'].value;
  if (x==null || x=='')
  {
    document.getElementById('mlw_support_message').innerHTML = '**Email must be filled out!**';
    return false;
  };
  var x=document.forms['emailForm']['username'].value;
  if (x==null || x=='')
  {
    document.getElementById('mlw_support_message').innerHTML = '**Name must be filled out!**';
    return false;
  };
  var x=document.forms['emailForm']['message'].value;
  if (x==null || x=='')
  {
    document.getElementById('mlw_support_message').innerHTML = '**There must be a message to send!**';
    return false;
  };
  var x=document.forms['emailForm']['email'].value;
  var atpos=x.indexOf('@');
  var dotpos=x.lastIndexOf('.');
  if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)
  {
    document.getElementById('mlw_support_message').innerHTML = '**Not a valid e-mail address!**';
    return false;
  }
}
