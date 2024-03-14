/**
 * Registers a new block provided a unique name and an object defining its behavior.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/block-api/block-registration/
 */
import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/edit-post';

/**
 * Lets webpack process CSS, SASS or SCSS files referenced in JavaScript files.
 * All files containing `style` keyword are bundled together. The code used
 * gets applied both to the front of your site and to the editor.
 *
 * @see https://www.npmjs.com/package/@wordpress/scripts#using-css
 */
import './style.scss';

function createMarkup() {
    if(typeof zmultilang_vars != 'undefined') {
        return {__html: zmultilang_vars.box};
    }
}

registerPlugin( 'zmultilang', {
    render() {
        return ( 
            <PluginDocumentSettingPanel title='Z Multi-Languages' initialOpen="true">
                 <div dangerouslySetInnerHTML={createMarkup()} />
            </PluginDocumentSettingPanel>
        );
    }
});
