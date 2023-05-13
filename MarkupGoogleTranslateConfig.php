<?php namespace Processwire;

use ProcessWire\ModuleConfig;

class MarkupGoogleTranslateConfig extends ModuleConfig {

    public function getDefaults() {
        return array(
          'default_name' => '',
          'first_option' => '',
          'custom_languages' => [],
          'icon' => 0,
          'icon_size' => 24,
          'wrapper' => 0,
          'div_classes' => '',
          'select_classes' => '',
        );
      }

    public function getInputfields() {

        $markDown  = wire('modules')->get('TextformatterMarkdownExtra');
 
// Text for usage notes
$text  = 'To show in all the pages of your site, put the code in a file globally loaded, eg. into your head declarations or nav bar functions.';
$text .= '<br>';
$text .= '<pre>if (wire("modules")->isInstalled("MarkupGoogleTranslate")) {
    echo wire("modules")->get("MarkupGoogleTranslate")->displayTranslateWidget();
}</pre>';

        
        $availableLanguages = wire('modules')->get('MarkupGoogleTranslate')->languageCodeNativeNames();
            
        $inputfields = parent::getInputfields();

        // -------------------------------------------------------------------------------
        // Inputfield wrapper for override settings
        $fsGlobal = wire('modules')->get('InputfieldFieldset');
        $fsGlobal->label = 'Settings';
        $fsGlobal->icon = "cogs";
        $fsGlobal->set("themeColor", "secondary");
        $fsGlobal->showIf = 'enable=1';
        // ($this->enable == 0) ? $fsGlobal->collapsed = Inputfield::collapsedYes : $fsGlobal->collapsed = Inputfield::collapsedNo ;

        // Enable/disable output
        $f = $this->modules->get('InputfieldCheckbox'); 
        $f->name = 'enable';
        $f->icon = 'toggle-on';
        $f->label = 'Enable module?';
        $f->label2 = 'Yes';
        (isset($data['enable'])) ? $f->checked($data['enable']) : $f->checked(0);
        $f->columnWidth = 100;
        $inputfields->add($f);
        
        // Usage notes
        $f = $this->modules->get('InputfieldMarkup'); 
        $f->name = 'usage'; 
        $f->icon = 'book';
        $f->label = 'Usage notes';
        if($markDown){
            $f->value = $markDown->markdown($text);
        } else {
            $f->value = $text;
        }
        // $f->set("themeColor", "highlight");
        $f->set("themeOffset", "m");
        $f->collapsed = Inputfield::collapsedYes;
        $f->showIf = 'enable=1';
        $inputfields->add($f);                  

        // default pw language name to pass as starting language
        $f = $this->modules->get('InputfieldText');
        $f->name = 'default_name';
        $f->icon = 'flag-checkered';
        $f->columnWidth = 50;
        $f->minlength = $f->maxlength = 2;
        $f->size = 1;
        $f->label = 'Name code as starting language';
        $f->description = 'ISO 639-1 code (two letters) to pass the "default" processwire language to Google Translation url as source language to start translating from';
        $f->notes = 'If blank, ProcessWire "default" language name will be passed as "en"';
        $f->value = (isset($data['default_name'])) ? $data['default_name'] : 'en';
        $fsGlobal->add($f);
        $inputfields->add($fsGlobal);

        // default pw language name to pass as starting language
        $f = $this->modules->get('InputfieldText');
        $f->name = 'first_option';
        $f->icon = 'font';
        $f->columnWidth = 50;
        $f->label = 'First option label for the html select tag';
        $f->description = 'Avoids specific multi language translation for this label
        
        ';
        $f->notes = 'If blank, will be populated as "Translate page"';
        $f->value = (isset($data['first_option'])) ? $data['first_option'] : '';
                $fsGlobal->add($f);
        $inputfields->add($fsGlobal);

        // NAtive names
        $f = $this->modules->get('InputfieldRadios'); 
        $f->name = 'native'; 
        $f->icon = 'book'; 
        $f->label = 'Show ISO Code/English/Native names for select option labels'; 
        $f->addOption(0,'Only ISO codes (two letters, uppercase)'); 
        $f->addOption(1,'Only English names'); 
        $f->addOption(2,'Only Native names'); 
        $f->addOption(3,'English and Native names (eg. "Italian - Italiano")'); 
        $f->addOption(4,'English in admin back-end, Native in front-end'); 
        $f->attr('value', 0); 
        $f->notes = 'Saving this setting, will also reflect the preview in the language selects'; 
        if(isset($data['native'])) $f->value = $data['native'];        
        $f->optionColumns = 0; 
        $f->columnWidth = 50; 
                $fsGlobal->add($f);
        $inputfields->add($fsGlobal);  


        // custom AsmSelect for available languages
        $f = $this->modules->get('InputfieldAsmSelect');
        $f->name = 'custom_languages';    
        $f->icon = 'language';
        $f->columnWidth = 50;
        $f->label = 'Languages to display into select options';
        $f->description = 'Select one or more languages to show in drop-down select options list';
        $f->notes = 'If blank, all available languages are populated as select options
        
        Into template page, it could be overrided by passing an array of ISO codes eg.:
        ```$translateModule = wire("modules")->get("MarkupGoogleTranslate");```
        ```echo $translateModule->displayTranslateWidget(["es","fr"]);```';
        foreach ($availableLanguages as $code => $names){    

            if($this->native == 0) $label = $code;
            if(($this->native == 1)||($this->native == 4)) $label = $names['name'];
            if($this->native == 2) $label = $names['native'];
            if($this->native == 3) $label = $names['name'] . ' - ' . $names['native'];
            

            $f->addOption($code,$label);
        }       
        if(isset($data['custom_languages'])) $f->value = $data['custom_languages'];
                $fsGlobal->add($f);
        $inputfields->add($fsGlobal);

        // Google Translation product icon
        $f = $this->modules->get('InputfieldRadios'); 
        $f->name = 'icon'; 
        $f->icon = 'google'; 
        $f->label = 'Show Google Translate product icon'; 
        $f->addOption(0,'No, do not show icon'); 
        $f->addOption(1, 'Yes, place on the LEFT'); 
        $f->addOption(2, 'Yes, place on the RIGHT'); 
        $f->attr('value', 0); 
        if(isset($data['icon'])) $f->value = $data['icon'];        
        $f->optionColumns = 1; 
        $f->columnWidth = 50; 
                $fsGlobal->add($f);
        $inputfields->add($fsGlobal);   
        
        // Icon size
        $f = $this->modules->get('InputfieldRadios'); 
        $f->name = 'icon_size'; 
        $f->icon = 'arrows-alt'; 
        $f->label = 'Size of the icon'; 
        $f->addOption(16,'Small, 16px'); 
        $f->addOption(24,'Normal, 24px'); 
        $f->addOption(32,'Big, 32px'); 
        $f->addOption(40,'Huge, 40px'); 
        $f->attr('value', 24); 
        if(isset($data['icon_size'])) $f->value = $data['icon_size'];        
        $f->optionColumns = 1; 
        $f->columnWidth = 50; 
        $f->showIf = 'icon!=0';
                $fsGlobal->add($f);
        $inputfields->add($fsGlobal);          

        // DIV wrapper
        $f = $this->modules->get('InputfieldCheckbox'); 
        $f->name = 'wrapper'; 
        $f->icon = 'code'; 
        $f->label = 'Wrap all into a div';
        $f->label2 = 'Yes';
        (isset($data['wrapper'])) ? $f->checked($data['wrapper']) : $f->checked(0);
        $f->columnWidth = 50;
        $f->description = 'Wrap both select and icon (if enabled) into a single div';
                $fsGlobal->add($f);
        $inputfields->add($fsGlobal);    

        // DIV classes
        $f = $this->modules->get('InputfieldText'); 
        $f->name = 'div_classes'; 
        $f->icon = 'file-code-o'; 
        $f->label = 'Optional classes for DIV styling';
        $f->description = 'Write classes as html attribute (without quotes, nor dots)';
        $f->notes = 'Eg. d-flex justify-content-between';
        if(isset($data['div_classes'])) $f->value = $data['div_classes'];        
        $f->columnWidth = 50;
        $f->showIf = 'wrapper=1';
                $fsGlobal->add($f);
        $inputfields->add($fsGlobal);

        // SELECT classes
        $f = $this->modules->get('InputfieldText'); 
        $f->name = 'select_classes'; 
        $f->icon = 'mouse-pointer'; 
        $f->label = 'Optional classes for SELECT styling';
        $f->description = 'Write classes as html attribute (without quotes, nor dots)';
        $f->notes = 'Eg. according to bootstrap framework: form-control form-control-sm';
        if(isset($data['select_classes'])) $f->value = $data['select_classes'];        
        $f->columnWidth = 100;
                $fsGlobal->add($f);
        $inputfields->add($fsGlobal);          

        // -------------------------------------------------------------------------------
        // Inputfield wrapper for override settings
        $fieldSet = wire('modules')->get('InputfieldFieldset');
        $fieldSet->label = 'Overrides';
        $fieldSet->icon = "strikethrough";
        $fieldSet->set("themeColor", "secondary");
        $fieldSet->showIf = 'enable=1';
        // ($this->overrides == 0) ? $fieldSet->collapsed = Inputfield::collapsedYes : $fieldSet->collapsed = Inputfield::collapsedNo ;
        
        // Check for overrides
        $f = $this->modules->get('InputfieldCheckbox'); 
        $f->name = 'overrides'; 
        $f->icon = 'check'; 
        $f->label = 'Enable override for templates or pages?';
        $f->label2 = 'Yes';
        (isset($data['overrides'])) ? $f->checked($data['overrides']) : $f->checked(0);
        $f->columnWidth = 100;
        $f->description = 'Enable override for specific templates by name or pages by id';
        $fieldSet->add($f);
        $inputfields->add($fieldSet);

        // -----------------------------------------------
        // MULTIPLE TPLS OVERRIDE
        // -----------------------------------------------          
        $f = $this->modules->get('InputfieldAsmSelect');
        $f->name = 'tpls';
        $f->icon = 'cubes'; 
        $f->label = 'Page templates for specific translations';
        $f->description = 'Select which templates will be overrided';
            $asmOptions = [];
            // template.flags!=8  == template!=admin 
            $limitedPages = wire('pages')->find('template!=admin, status!=unpublished, status!=hidden, has_parent!=2, include=all, sort=template.name');
            foreach ($limitedPages as $lP){
                $asmOptions[$lP->template->id] = $lP->template->name;
            }
        $f->options = $asmOptions;
        $f->columnWidth = 50;
        $f->showIf = 'overrides=1';
        $fieldSet->add($f);
        $inputfields->add($fieldSet);

        // custom AsmSelect for available languages
        $f = $this->modules->get('InputfieldAsmSelect');
        $f->name = 'multiple_override';    
        $f->icon = 'language';
        $f->columnWidth = 50;
        $f->label = 'Languages to display into select options for Overrided temlpates';
        $f->description = 'Select one or more languages to show in drop-down select options list';
        $f->notes = 'If blank, all available languages are populated as select options';
        $f->showIf = 'overrides=1';
        foreach ($availableLanguages as $code => $names){

            if($this->native == 0) $label = $code;
            if(($this->native == 1)||($this->native == 4)) $label = $names['name'];
            if($this->native == 2) $label = $names['native'];
            if($this->native == 3) $label = $names['name'] . ' - ' . $names['native'];

            $f->addOption($code,$label);
        }       
        if(isset($data['multiple_override'])) $f->value = $data['multiple_override'];
        $fieldSet->add($f);
        $inputfields->add($fieldSet);


        // -----------------------------------------------
        // SINGLE TPL OVERRIDE
        // -----------------------------------------------          
        $f = $this->modules->get('InputfieldSelect');
        $f->name = 'tpl';
        $f->icon = 'cube'; 
        $f->label = 'Single template for specific translations';
        $f->description = 'Select which template will be overrided';
            $asmOptions = [];
            // template.flags!=8  == template!=admin 
            $limitedPages = wire('pages')->find('template!=admin, status!=unpublished, status!=hidden, has_parent!=2, include=all, sort=template.name');
            foreach ($limitedPages as $lP){
                $asmOptions[$lP->template->id] = $lP->template->name;
            }
        $f->options = $asmOptions;
        $f->columnWidth = 50;
        $f->showIf = 'overrides=1';
        $fieldSet->add($f);
        $inputfields->add($fieldSet);

        // custom AsmSelect for available languages
        $f = $this->modules->get('InputfieldAsmSelect');
        $f->name = 'single_override';    
        $f->icon = 'language';
        $f->columnWidth = 50;
        $f->label = 'Languages to display into select options for a SINGLE temlpate';
        $f->description = 'Select one or more languages to show in drop-down select options list';
        $f->notes = 'If blank, all available languages are populated as select options';
        $f->showIf = 'overrides=1';
        foreach ($availableLanguages as $code => $names){

            if($this->native == 0) $label = $code;
            if(($this->native == 1)||($this->native == 4)) $label = $names['name'];
            if($this->native == 2) $label = $names['native'];
            if($this->native == 3) $label = $names['name'] . ' - ' . $names['native'];

            $f->addOption($code,$label);
        }       
        if(isset($data['single_override'])) $f->value = $data['single_override'];
        $fieldSet->add($f);
        $inputfields->add($fieldSet);


        // -----------------------------------------------
        // PAGES OVERRIDE
        // -----------------------------------------------          
        $f = $this->modules->get('InputfieldText');
        $f->name = 'page_ids';
        $f->icon = 'file-o'; 
        $f->label = 'Page IDs for specific translations';
        $f->description = 'List the IDs of the pages to override';
        $f->notes = 'Single id, list separated by pipe (\'|\') or ranges of ids separated by hyphen (\'-\'), eg.:
        ```1030``` means page id 1030
        ```1030|2025``` means page id 1030 and page id 2025
        ```1030-1035``` means page id 1030, 1031, 1032, 1033, 1034 and 1035';
        // $f->options = $asmOptions;
        $f->columnWidth = 50;
        $f->showIf = 'overrides=1';
        if(isset($data['page_ids'])) $f->value = $data['page_ids'];
        $fieldSet->add($f);
        $inputfields->add($fieldSet);

        // custom AsmSelect for available languages
        $f = $this->modules->get('InputfieldAsmSelect');
        $f->name = 'pages_override';    
        $f->icon = 'language';
        $f->columnWidth = 50;
        $f->label = 'Languages to display into select options for page IDs';
        $f->description = 'Select one or more languages to show in drop-down select options list';
        $f->notes = 'If blank, all available languages are populated as select options';
        $f->showIf = 'overrides=1';
        foreach ($availableLanguages as $code => $names){

            if($this->native == 0) $label = $code;
            if(($this->native == 1)||($this->native == 4)) $label = $names['name'];
            if($this->native == 2) $label = $names['native'];
            if($this->native == 3) $label = $names['name'] . ' - ' . $names['native'];

            $f->addOption($code,$label);
        }       
        if(isset($data['pages_override'])) $f->value = $data['pages_override'];
        $fieldSet->add($f);
        $inputfields->add($fieldSet);


        // --------------------------------------------------------
        // SURGICAL PAGE OVERRIDES (Language and Starting iso code)
        // --------------------------------------------------------          
        $f = $this->modules->get('InputfieldTextarea');
        $f->name = 'surgical';
        $f->icon = 'crosshairs'; 
        $f->label = 'Translation "surgical" override';
        $f->description = 'List the ```page.id=isocode``` to set specific translation options for that page. Write each condition on single line.
        **N.B. This overrides previous settings above.**';
        $f->notes = '```1030=it``` means page id 1030 will have Italian in dropdown options list
        ```1030=it|en```  means page id 1030 will have both Italian and English in dropdown options list
        ```1030|1031=it|en```  means pages with id 1030 and 1031 will have both Italian and English in dropdown options list
        ```1033-1034=de|en|fr```  means pages with id between 1033 and 1034 will have German, English and French in dropdown options list';
        $f->showIf = 'overrides=1';
        $f->columnWidth = 50;
        if(isset($data['surgical'])) $f->value = $data['surgical'];
        $fieldSet->add($f);
        $inputfields->add($fieldSet);

        $f = $this->modules->get('InputfieldTextarea');
        $f->name = 'surgical_starting';
        $f->icon = 'flag-checkered'; 
        $f->label = 'Starting "surgical" override';
        $f->description = 'List the ```page.id=isocode``` to set specific **starting language** to pass to Google Translate for that page. Write each condition on single line.
        **N.B. This overrides default starting language settings above.**';
        $f->notes = '```1030=it``` means page id 1030 will have Italian as starting language
        ```1030|1031=en```  means pages with id 1030 and 1031 will pass English as starting language
        ```1033-1034=de```  means pages with id between 1033 and 1034 will pass German as starting language';
        $f->showIf = 'overrides=1';
        $f->columnWidth = 50;
        if(isset($data['surgical_starting'])) $f->value = $data['surgical_starting'];
        $fieldSet->add($f);
        $inputfields->add($fieldSet);        


        return $inputfields;
        // return $fsGlobal;
    }

}