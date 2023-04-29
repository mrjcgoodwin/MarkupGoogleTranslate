
# MarkupGoogleTranslate for Processwire

A very simple module that outputs a widget allowing a visitor to your Processwire website to translate the current page using Google translate.

**Note:** This module does **_not_** utitlise the Google Translate API. It simply redirects the visitor to the online web page translator which is part of Google's publically available online translator service. (https://translate.google.com/)

This module was originally built for a specific project, in response to Google deprecating their embeddable translation widget for most users. _(They still allow you to apply for usage if you are in academia and a few other sectors)._

Usage and continued operation of this module is at the mercy of Google allowing us to redirect traffic to their translation service in this way.

## Usage
After you have installed the module to your ProcesWire installation the module will be available in any page template as follows:

 ```<?php $translate = $modules->get('MarkupGoogleTranslate');?>```

You can output a simple translation widget in your page template:
 ```<?php echo $translate->displayTranslateWidget();?>```

This will display a basic HTML select field so visitors can choose from available languages. On selecting a language the visitor will be redirected to the translated page.

The Google translate toolbar will display at the top of the page from this point forward allowing visitors to select other languages via the default Google UI.

You can also create a language specific translation link using the public `buildGoogleTranslateUrl` method and supplying it with an ISO language code as. E.g. for a link to a Spanish version of your page:

 ```<?php echo buildGoogleTranslateUrl('es');?>```

### Available Languages

Available languages and ISO codes for translation based on https://cloud.google.com/translate/docs/languages

| Language | ISO Code |
|----------|----------|
|Afrikaans|af|
|Albanian|sq|
|Amharic|am|
|Arabic|ar|
|Armenian|hy|
|Azerbaijani|az|
|Basque|eu|
|Belarusian|be|
|Bengali|bn|
|Bosnian|bs|
|Bulgarian|bg|
|Catalan|ca|
|Cebuano|ceb|
|Chinese (Simplified)|zh-CN|
|Chinese (Traditional)|zh-TW|
|Corsican|co|
|Croatian|hr|
|Czech|cs|
|Danish|da|
|Dutch|nl|
|English|en|
|Esperanto|eo|
|Estonian|et|
|Finnish|fi|
|French|fr|
|Frisian|fy|
|Galician|gl|
|Georgian|ka|
|German|de|
|Greek|el|
|Gujarati|gu|
|Haitian Creole|ht|
|Hausa|ha|
|Hawaiian|haw|
|Hebrew|he|
|Hindi|hi|
|Hmong|hmn|
|Hungarian|hu|
|Icelandic|is|
|Igbo|ig|
|Indonesian|id|
|Irish|ga|
|Italian|it|
|Japanese|ja|
|Javanese|jv|
|Kannada|kn|
|Kazakh|kk|
|Khmer|km|
|Kinyarwanda|rw|
|Korean|ko|
|Kurdish|ku|
|Kyrgyz|ky|
|Lao|lo|
|Latin|la|
|Latvian|lv|
|Lithuanian|lt|
|Luxembourgish|lb|
|Macedonian|mk|
|Malagasy|mg|
|Malay|ms|
|Malayalam|ml|
|Maltese|mt|
|Maori|mi|
|Marathi|mr|
|Mongolian|mn|
|Myanmar (Burmese)|my|
|Nepali|ne|
|Norwegian|no|
|Nyanja (Chichewa)|ny|
|Odia (Oriya)|or|
|Pashto|ps|
|Persian|fa|
|Polish|pl|
|Portuguese|pt|
|Punjabi|pa|
|Romanian|ro|
|Russian|ru|
|Samoan|sm|
|Scots Gaelic|gd|
|Serbian|sr|
|Sesotho|st|
|Shona|sn|
|Sindhi|sd|
|Sinhala (Sinhalese)|si|
|Slovak|sk|
|Slovenian|sl|
|Somali|so|
|Spanish|es|
|Sundanese|su|
|Swahili|sw|
|Swedish|sv|
|Tagalog (Filipino)|tl|
|Tajik|tg|
|Tamil|ta|
|Tatar|tt|
|Telugu|te|
|Thai|th|
|Turkish|tr|
|Turkmen|tk|
|Ukrainian|uk|
|Urdu|ur|
|Uyghur|ug|
|Uzbek|uz|
|Vietnamese|vi|
|Welsh|cy|
|Xhosa|xh|
|Yiddish|yi|
|Yoruba|yo|
|Zulu|zu|