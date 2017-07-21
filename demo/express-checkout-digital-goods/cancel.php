<?php
/**
 * This page actually does not get viewed in the browser,
 * but simply loads to close the checkout window from the
 * Digital Goods payment experience.
 *
 * Without this page in tact, the user could get stuck
 * with the payment window on top of the screen when
 * they attempt to close the payment window.
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PayPal Express Checkout Digital Goods Demo | Cancel Order | PHP Class Library | Angell EYE</title>
</head>

<body>
<p>Order Canceled</p>
<script>
window.onload = function(){
	 if(window.opener){
		 window.close();
	} 
	 else{
		 if(top.dg.isOpen() == true){
              top.dg.closeFlow();
              return true;
          }
     }                              
};                             
</script>
</body>
</html>