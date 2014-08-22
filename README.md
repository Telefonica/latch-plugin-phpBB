#LATCH INSTALLATION GUIDE FOR phpBB


##PREREQUISITES 
* phpBB version 3.0.X.

* Curl extensions active in PHP (uncomment "extension=php_curl.dll" or "extension=curl.so" in Windows or Linux php.ini respectively. 

* To get the **"Application ID"** and **"Secret"**, (fundamental values for integrating Latch in any application), it’s necessary to register a developer account in [Latch's website](https://latch.elevenpaths.com). On the upper right side, click on **"Developer area"**. 



###DOWNLOADING THE MODULE
 * When the account is activated, the user will be able to create applications with Latch and access to developer documentation, including existing SDKs and plugins. The user has to access again to [Developer area](https://latch.elevenpaths.com/www/developerArea), and browse his applications from **"My applications"** section in the side menu.

* When creating an application, two fundamental fields are shown: **"Application ID"** and **"Secret"**, keep these for later use. There are some additional parameters to be chosen, as the application icon (that will be shown in Latch) and whether the application will support OTP  (One Time Password) or not.

* From the side menu in developers area, the user can access the **"Documentation & SDKs"** section. Inside it, there is a **"SDKs and Plugins"** menu. Links to different SDKs in different programming languages and plugins developed so far, are shown.

##INSTALLING THE MODULE
* Once the administrator has downloaded the module, copy its content in phpBB root folder.

* Next step is to activate Latch module. From control panel, go to **SYSTEM** tab, and then to
 **User Control Panel**. Select from the menu **Latch configuration** and press **add module** button.
 
* After accepting the message, go back to **User Control Panel**, where there will be a table with installed modules. Latch will be the last one.

* Next to **Latch configuration** text are the options available for the module. You must press on **Enable** to activate it. Last configuration is for removing the module.


###CONFIGURING THE INSTALLED MODULE
* Next step is to include *Application Id* and *Secret* previously generated. Got to **General** tab, and to **Authentication**. The existing authenticating method should be replaced in the selectbox, indicating that from now on, authentication based in **Latch** is added.

* The second selectbox only appears when Latch is installed, and indicates the method Latch uses for authentication. This method must be the one that was previously indicated in the selectbox above.

* Press **submit** to end with module configuration.

* The module is now ready to be used. There will be a new tab **Latch Configuration** in user control panel. Go to **Board index - User Control Panel**.

##UNINSTALLING THE MODULE IN phpBB
* To fully uninstall, the steps taken during the installation has to be undone:
	1. Press **delete** in the installed list of modules.
	2. State back the previous authentication module.
	3. Remove the files copied during installation.

##USE OF LATCH MODULE FOR THE USERS

**Latch does not affect in any case or in any way the usual operations with an account. It just allows or denies actions over it, acting as an independent extra layer of security that, once removed or without effect, will have no effect over the accounts, which will remain with their original state.**

###Pairing a user in phpBB
The user needs the Latch application installed on the phone, and follow these steps:

* **Step 1:** Pairing phpBB account with Latch. go to **User Control Panel**, and click on the new tab **Latch Configuration**. 

* **Step 2:** From the Latch app on the phone, the user has to generate the token, pressing on **“Add a new service"** at the bottom of the application, and pressing **"Generate new code"** will take the user to a new screen where the pairing code will be displayed.

* **Step 3:** The user has to type the characters generated on the phone into the text box displayed on the web page. Click on **"Pair"** button.

* **Step 4:** Now the user may lock and unlock the account, preventing any unauthorized access.
 

###Unpairing a user in phpBB
The user has to log on into his phpBB account, go to **User Control Panel**, and click on the new tab **Latch Configuration**, then click on **Unpair** button. He will receive a notification indicating that the service has been unpaired.      


##RESOURCES
- You can access Latch´s use and installation manuals, together with a list of all available plugins here: [https://latch.elevenpaths.com/www/developers/resources](https://latch.elevenpaths.com/www/developers/resources)

- Further information on de Latch´s API can be found here: [https://latch.elevenpaths.com/www/developers/doc_api](https://latch.elevenpaths.com/www/developers/doc_api)

- For more information about how to use Latch and testing more free features, please refer to the user guide in Spanish and English:
	1. [English version](https://latch.elevenpaths.com/www/public/documents/howToUseLatchNevele_EN.pdf)
	1. [Spanish version](https://latch.elevenpaths.com/www/public/documents/howToUseLatchNevele_ES.pdf)