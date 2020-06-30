var el = wp.element.createElement;

wp.blocks.registerBlockType( 'ctcl-election-website/numbered-section-block', {
    title: 'Numbered Section',
    icon: 'editor-ol',
    category: 'common',

    edit: function( props ) {
        return el( 'section',
            {
                className: 'numbered-section-block-editor'
            },
            el( 'div',
                {
                    className: 'numbered-section-block-editor-counter'
                }
            ),
            el( 'div',
                {
                    className: 'numbered-section-block-editor-content'
                },
                el( wp.blockEditor.InnerBlocks )
            )
        );
    },

    save: function( props ) {
        return el( 'section',
            {
                className: 'numbered-section'
            },
            el( 'div',
                {
                    className: 'numbered-section-content'
                },
                el( wp.blockEditor.InnerBlocks.Content )
            )
        );
    }
} );

// var el = wp.element.createElement;

// wp.blocks.registerBlockType('ctcl-election-website/tile-nav-block', {
//    title: 'Tile Navigation', // Block name visible to user
//    icon: 'screenoptions', // Toolbar icon can be either using WP Dashicons or custom SVG
//    category: 'common', // Under which category the block would appear
//    attributes: { // The data this block will be storing

//       // type: { type: 'string', default: 'default' }, // Notice box type for loading the appropriate CSS class. Default class is 'default'.

//       // title: { type: 'string' }, // Notice box title in h4 tag

//       // content: { type: 'array', source: 'children', selector: 'p' } /// Notice box content in p tag

//       icon: {
//          type: 'string',
//       },
//       title: {
//          type: 'string'
//       },
//       link: {
//          type: 'string'
//       }
//    },

//    edit: function(props) {
//     // How our block renders in the editor in edit mode

//     const pageSelections = [{ value: "1", label: "2" }];
//     wp.apiFetch({path: "/wp/v2/pages"}).then(pages => {
//       // pageSelections.push({label: "Select a Page", value: 0});
//       pages.forEach((page) => {
//          pageSelections.push({
//             label: page.title.rendered,
//             value: page.link  // get id somehow and map it on edit / save
//          })
//       });
//       console.log(pages, pageSelections);
//       return pageSelections;
//    });

//    console.log(wp.data.select('core').getEntityRecords('taxonomy', 'category'));

//     function updateTitle( event ) {
//        props.setAttributes( { title: event.target.value } );
//     }

//     function updateLink( event ) {
//        props.setAttributes( { link: event.target.value } );
//    }

//     function updateContent( newdata ) {
//        props.setAttributes( { content: newdata } );
//     }

//     function updateIcon( event ) {
//        props.setAttributes( { icon: event.target.value } );
//     }

//     return el( 'div',
//       {
//           className: 'notice-box notice-' + props.attributes.type
//       },
//       //  el(
//       //     'select',
//       //     {
//       //        onChange: updateType,
//       //        value: props.attributes.type,
//       //     },
//       //     el("option", {value: "default" }, "Default"),
//       //     el("option", {value: "success" }, "Success"),
//       //     el("option", {value: "danger" }, "Danger")
//       //  ),
//       //  el(
//       //     'input',
//       //     {
//       //        type: 'text',
//       //        placeholder: 'Enter title here...',
//       //        value: props.attributes.title,
//       //        onChange: updateTitle,
//       //        style: { width: '100%' }
//       //     }
//       //  ),
//       //  el(
//       //     wp.editor.RichText,
//       //     {
//       //        tagName: 'p',
//       //        onChange: updateContent,
//       //        value: props.attributes.content,
//       //        placeholder: 'Enter description here...'
//       //     }
//       //  )
//       // el(
//       //    'select',
//       //    {
//       //       onChange: updateIcon,
//       //       value: props.attributes.icon,
//       //    },
//       //    el("option", {value: "default" }, "Default"),
//       //    el("option", {value: "success" }, "Success"),
//       //    el("option", {value: "danger" }, "Danger")
//       // ),
//       el(
//          wp.components.SelectControl,
//          {
//             placeholder: 'Select Icon',
//             options: [
//                { value: "default", label: "Default" },
//                { value: "success", label: "Success" },
//                { value: "danger", label: "Danger" }
//             ],
//             onChange: updateIcon,
//             value: props.attributes.icon
//          }
//       ),
//       el(
//          wp.components.TextControl,
//          {
//             type: 'text',
//             placeholder: 'Enter title',
//             value: props.attributes.title,
//             onChange: updateTitle,
//          }
//       ),
//       el(
//          wp.components.SelectControl,
//          {
//             label: 'Select a Page',
//             options: pageSelections,
//             value: props.attributes.link,
//             onChange: updateLink,
//          },
//       ),
//       el(
//          wp.editor.InnerBlocks,
//       )
//     );
//  },

//  save: function(props) {
//     // How our block renders on the frontend

//     return el( 'div',
//        {
//           className: 'notice-box notice-' + props.attributes.type
//        },
//        el(
//           'h4',
//           null,
//           props.attributes.title
//        ),
//        el( wp.editor.RichText.Content, {
//           tagName: 'p',
//           value: props.attributes.content
//        }),
//       //  el( wp.editor.InnerBlocks.content, {

//       //  })

//     ); // End return

//  } // End save()
// });

//# sourceMappingURL=blocks.src.js.map