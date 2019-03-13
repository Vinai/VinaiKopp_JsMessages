# This Magento 1 extension is orphaned, unsupported and no longer maintained.

If you use it, you are effectively adopting the code for your own project.

Client Side Message Rendering
=============================
This extensions aims to be a drop in replacement for the Magento `core/message` block, causing session splash messages to be rendered client side in the browser using JavaScript.  

Facts
-----
- Version: check the [config.xml](https://github.com/Vinai/VinaiKopp_JsMessages/blob/master/app/code/community/VinaiKopp/JsMessages/etc/config.xml)
- Extension key: - This extension is not on Magento Connect (github only) -
- [Extension on GitHub](https://github.com/Vinai/VinaiKopp_JsMessage)
- [Direct download link](https://github.com/Vinai/VinaiKopp_JsMessage/zipball/master)

Description
-----------
This extensions aims to be a drop in replacement for the Magento `core/message` block, causing session splash messages to be rendered client side in the browser using JavaScript.  
The splash messages are sent using a cookie `jsmessages`.  

This extension probably is mainly useful when implementing some kind of full page cache, for example https://github.com/colinmollenhour/Cm_Diehard.

Compatibility
-------------
- Magento >= 1.8 (probably also earlier)

Installation Instructions
-------------------------
1. If you use the Magento compiler, disable compilation mode.
2. Unpack the extension ZIP file in your Magento root directory.
3. Clear the cache.
4. If you use the Magento compiler, recompile.

Support
-------
If you have any issues with this extension, open an issue on GitHub (see URL above)

Contribution
------------
Any contributions are highly appreciated. The best way to contribute code is to open a
[pull request on GitHub](https://help.github.com/articles/using-pull-requests).

Developer
---------
Vinai Kopp  
[http://vinaikopp.com](http://vinaikopp.com)  
[@VinaiKopp](https://twitter.com/VinaiKopp)  

Licence
-------
[OSL - Open Software Licence 3.0](http://opensource.org/licenses/osl-3.0.php)

Copyright
---------
(c) 2014 Vinai Kopp
