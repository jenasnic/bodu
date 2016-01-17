Allows to manage files through back office (i.e. multimedia library).

1/ Add CSS + pictures (in 'Resources/public/css' directory) in website's resources ('/web/resources/css') => theme for user file bundle in back-office
NOTE : include CSS 'userfile.css' when using CLEditor

2/ Add scripts (in 'Resources/public/js' directory) in website's resources ('/web/resources/js')
NOTE : include scripts 'plugin.cledito.userfile.js' and 'userFileExplorer.js' when using CLEditor

3/ Add picture 'Resources/public/images/empty.png' in website's resources ('/web/resources/images')

4/ Initialize CLEditor with control 'userfile' : $('#wysiwyg').cleditor({controls: "userfile"});

NOTE : All uploaded files are stored in '/web/userfiles/misc' directory. You can change this directory in configuration file 'Resources/config/services.yml'.
