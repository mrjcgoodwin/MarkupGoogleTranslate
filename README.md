# MarkupGoogleTranslate for Processwire
Original repo at: https://github.com/mrjcgoodwin/MarkupGoogleTranslate/

## Cybermano fork

### Changeleogs

#### [V0.0.7] - Language code passed to Google interface and restored ```$scheme``` (https/http)
Passed ```$languageIsoCode```to the url chunk ```hl=XX``` instead of ```hl=en```to match user required language
Restored ```$scheme``` (https/http) in the url previously removed (ad originally not used in the forked repo)

#### [V0.0.6] - Restored only https scheme
It seems that Google accepts only SSL certificates on mobile...

#### [V0.0.5] - Google Icon, wrapper, styles, first option label and specific languages array
Configurable options to show Google Translator Product icon, wrap icon and select into a div, styling classes to pass either to the DIV and to the SELECT, customizable first select option label and restored specific translations.

Calling method is now as original module:

 ```<?php $translate = $modules->get('MarkupGoogleTranslate');?>```
 ```<?php echo $translate->displayTranslateWidget();?>```

or in one code line:

 ```<?php echo $modules->get('MarkupGoogleTranslate')->displayTranslateWidget();?>```

Restored the specific language override as public call, passing an array of language codes (for single languages or multiple ones):

 ```<?php echo $modules->get('MarkupGoogleTranslate')->buildGoogleTranslateUrl(['es','fr']);?>```

#### [V0.0.4] - Custom languages setted in module [MAJOR]
Added InputfieldAsmSelect to limit by module settings the languages shown in frontend drop-down list.
N.B. Workaround: InputfieldAsmSelect won\'t return $key=>$value in fronend; added private method to return google available languages flipped array.

```<?php echo $translate->displayTranslateWidget(['div'=>'d-flex justify-content-between','select'=>'form-control form-control-sm'],TRUE); ?>```

#### [V0.0.3] - Starting translation language setted in module.
Added configurable field in module to override source language code to pass to google tranlation url; after that, simplifyed the method parameters to pass default language code;
Also corrected a typo in $translateUrl that previously outputted a double slash in the chunk:

 ```'translate.goog'.$currentPagePath ```

Inserted the https $scheme in the url, that was probabily missed.

#### [V0.0.2] - Classes for styling.
Added two more parameters to better style select options:

 ```<?php echo $translate->buildGoogleTranslateUrl($languages,['div'=>'d-inline-block','select'=>'form-control'],TRUE);?>``` 

#### [V0.0.1] - Limit drop-down list.
Now can pass only custom languages, to shorten dropdown menu for language options:

 ```<?php $languages = ["Deutsch" => "de","English" => "en","Italiano" => "it"]; ?> ```
 ```<?php echo $translate->buildGoogleTranslateUrl($languages);?>```
