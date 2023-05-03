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
        
        $availableLanguages = array_flip(wire('modules')->get('MarkupGoogleTranslate')->availableLanguages());
            
        $inputfields = parent::getInputfields();

        // default pw language name to pass as starting language
        $f = $this->modules->get('InputfieldText');
        $f->name = 'default_name';
        $f->icon = 'flag-checkered';
        $f->columnWidth = 25;
        $f->minlength = $f->maxlength = 2;
        $f->size = 1;
        $f->label = 'Name code as starting language';
        $f->description = 'ISO 639-1 code (two letters) to pass the "default" processwire language to Google Translation url as source language to start translating from';
        $f->notes = 'If blank, ProcessWire "default" language name will be passed as "en"';
        $f->value = (isset($data['default_name'])) ? $data['default_name'] : 'en';
        $inputfields->add($f);

        // default pw language name to pass as starting language
        $f = $this->modules->get('InputfieldText');
        $f->name = 'first_option';
        $f->icon = 'font';
        $f->columnWidth = 25;
        $f->label = 'First option label for the html select tag';
        $f->description = 'Avoids specific multi language translation for this label
        
        ';
        $f->notes = 'If blank, will be populated as "Translate page"';
        $f->value = (isset($data['first_option'])) ? $data['first_option'] : '';
        $inputfields->add($f);

        // custom AsmSelect for available languages
        $f = $this->modules->get('InputfieldAsmSelect');
        $f->name = 'custom_languages';    
        $f->icon = 'language';
        $f->columnWidth = 50;
        $f->label = 'Languages to display into select options';
        $f->description = 'Select one or more languages to show in drop-down select options list';
        $f->notes = 'If blank, all available languages are populated as select options
        
        Into template page, it could be overrided by passing an array of ISO codes eg.:
        ```echo wire("modules")->get("MarkupGoogleTranslate")->displayTranslateWidget(["es","fr"]);```';
        foreach ($availableLanguages as $code => $title){
            $f->addOption($code,$title);
        }       
        if(isset($data['custom_languages'])) $f->value = $data['custom_languages'];
        $inputfields->add($f);

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
        $inputfields->add($f);   
        
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
        $inputfields->add($f);          

        // DIV wrapper
        $f = $this->modules->get('InputfieldCheckbox'); 
        $f->name = 'wrapper'; 
        $f->icon = 'code'; 
        $f->label = 'Wrap all into a div';
        $f->label2 = 'Yes';
        (isset($data['wrapper'])) ? $f->checked($data['wrapper']) : $f->checked(0);
        $f->columnWidth = 50;
        $f->description = 'Wrap both select and icon (if enabled) into a single div';
        $inputfields->add($f);    

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
        $inputfields->add($f);

        // SELECT classes
        $f = $this->modules->get('InputfieldText'); 
        $f->name = 'select_classes'; 
        $f->icon = 'mouse-pointer'; 
        $f->label = 'Optional classes for SELECT styling';
        $f->description = 'Write classes as html attribute (without quotes, nor dots)';
        $f->notes = 'Eg. according to bootstrap framework: form-control form-control-sm';
        if(isset($data['select_classes'])) $f->value = $data['select_classes'];        
        $f->columnWidth = 100;
        $inputfields->add($f);          

        // -------------------------------------------------------------------------------
        // Inputfield wrapper for override settings
        $fieldSet = wire('modules')->get('InputfieldFieldset');
        $fieldSet->label = 'Template overrides';
        $fieldSet->icon = "strikethrough";
        $fieldSet->set("themeColor", "primary");
        $fieldSet->collapsed = Inputfield::collapsedYes;        

        // Check for overrides
        $f = $this->modules->get('InputfieldCheckbox'); 
        $f->name = 'overrides'; 
        $f->icon = 'check'; 
        $f->label = 'Enable template override';
        $f->label2 = 'Yes';
        (isset($data['overrides'])) ? $f->checked($data['overrides']) : $f->checked(0);
        $f->columnWidth = 100;
        $f->description = 'Enable override for specific templates';
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
        foreach ($availableLanguages as $code => $title){
            $f->addOption($code,$title);
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
        foreach ($availableLanguages as $code => $title){
            $f->addOption($code,$title);
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
        $f->options = $asmOptions;
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
        foreach ($availableLanguages as $code => $title){
            $f->addOption($code,$title);
        }       
        if(isset($data['pages_override'])) $f->value = $data['pages_override'];
        $fieldSet->add($f);
        $inputfields->add($fieldSet);


        return $inputfields;
    }

}