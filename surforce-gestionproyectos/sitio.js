// JavaScript Document

// Borra espacios en blanco al principio y al final de la cadena
function trim(str)
{
   return str.replace(/^\s*|\s*$/g,"");
}

// Verifica si una cadena es numérica
function IsNumeric(sText)
{
   var ValidChars = "0123456789";
   var IsNumber=true;
   var Char;
   
   sText = trim(sText);
 
   for (i = 0; i < sText.length && IsNumber == true; i++) 
      { 
      Char = sText.charAt(i); 
      if (ValidChars.indexOf(Char) == -1) 
         {
         IsNumber = false;
         }
      }
   return IsNumber;   
   }