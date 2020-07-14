(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";

var registerBlockType = wp.blocks.registerBlockType;
var createElement = wp.element.createElement;
var _wp$blockEditor = wp.blockEditor,
    InspectorControls = _wp$blockEditor.InspectorControls,
    InnerBlocks = _wp$blockEditor.InnerBlocks,
    RichText = _wp$blockEditor.RichText;
var _wp$components = wp.components,
    PanelBody = _wp$components.PanelBody,
    PanelRow = _wp$components.PanelRow,
    SelectControl = _wp$components.SelectControl;
var PARENT_BLOCK = 'ctcl-election-website/accordion-group-block';
var CHILD_BLOCK = 'ctcl-election-website/accordion-section-block';
var DEFAULT_HEADER_TAG = 'h1';
var TEMPLATE = [[CHILD_BLOCK, {
  headerTag: DEFAULT_HEADER_TAG
}]];
var AccordionBlockContext = wp.element.createContext(DEFAULT_HEADER_TAG);
registerBlockType(CHILD_BLOCK, {
  title: 'Collapsible Section',
  icon: 'book',
  category: 'election-blocks',
  parent: [PARENT_BLOCK],
  attributes: {
    headerTag: {
      type: 'string',
      default: DEFAULT_HEADER_TAG
    },
    heading: {
      type: 'array',
      source: 'children',
      selector: '.accordion-section-header'
    }
  },
  edit: function edit(props) {
    function updateHeading(newdata) {
      props.setAttributes({
        heading: newdata
      });
    }

    return /*#__PURE__*/React.createElement("div", {
      className: "accordion-section-editor"
    }, /*#__PURE__*/React.createElement(AccordionBlockContext.Consumer, null, function (value) {
      props.setAttributes({
        headerTag: value
      });
    }), /*#__PURE__*/React.createElement(InspectorControls, null, /*#__PURE__*/React.createElement(PanelBody, {
      title: "Specify section settings",
      initialOpen: true
    })), /*#__PURE__*/React.createElement(RichText, {
      className: "accordion-section-header",
      tagName: props.attributes.headerTag,
      onChange: updateHeading,
      value: props.attributes.heading,
      placeholder: "Enter header here..."
    }), /*#__PURE__*/React.createElement(InnerBlocks, {
      className: "accordion-section-content-editor"
    }));
  },
  save: function save(props) {
    return /*#__PURE__*/React.createElement("div", {
      className: "accordion-section-wrapper ".concat('h5' === props.attributes.headerTag ? 'subsection' : '')
    }, createElement(props.attributes.headerTag, {
      className: 'accordion-section-header'
    }, props.attributes.heading), /*#__PURE__*/React.createElement("section", {
      className: "accordion-section-content"
    }, /*#__PURE__*/React.createElement(InnerBlocks.Content, null)));
  }
});
registerBlockType(PARENT_BLOCK, {
  title: 'Collapsible Group',
  icon: 'book',
  category: 'election-blocks',
  attributes: {
    headerTag: {
      type: 'string',
      default: DEFAULT_HEADER_TAG
    }
  },
  edit: function edit(props) {
    var clientId = props.clientId;
    return /*#__PURE__*/React.createElement("div", {
      className: "accordion-group-editor"
    }, /*#__PURE__*/React.createElement(InspectorControls, null, /*#__PURE__*/React.createElement(PanelBody, {
      title: "Collapsible Group Settings",
      initialOpen: true
    }, /*#__PURE__*/React.createElement(PanelRow, null, /*#__PURE__*/React.createElement(SelectControl, {
      label: "Header Style",
      value: props.attributes.headerTag,
      options: [{
        label: 'H1 headers',
        value: 'h1'
      }, {
        label: 'H3 headers with icon',
        value: 'h3'
      }, {
        label: 'H5 headers (subsections)',
        value: 'h5'
      }],
      onChange: function onChange(val) {
        return props.setAttributes({
          headerTag: val
        });
      }
    })))), /*#__PURE__*/React.createElement(AccordionBlockContext.Provider, {
      value: props.attributes.headerTag
    }, /*#__PURE__*/React.createElement(InnerBlocks, {
      className: "accordion-group-wrapper",
      allowedBlocks: [CHILD_BLOCK],
      template: TEMPLATE
    })));
  },
  save: function save(props) {
    return /*#__PURE__*/React.createElement("section", {
      className: "accordion-group ".concat('h5' === props.attributes.headerTag ? 'subsection' : '')
    }, /*#__PURE__*/React.createElement(InnerBlocks.Content, null));
  }
});

},{}],2:[function(require,module,exports){
"use strict";

var registerBlockType = wp.blocks.registerBlockType;
var _wp = wp,
    ServerSideRender = _wp.serverSideRender;
var Disabled = wp.components.Disabled;
registerBlockType('ctcl-election-website/contact-form', {
  title: 'Contact Form',
  icon: 'email',
  category: 'election-blocks',
  edit: function edit(props) {
    return /*#__PURE__*/React.createElement(Disabled, null, /*#__PURE__*/React.createElement(ServerSideRender, {
      block: "ctcl-election-website/contact-form",
      attributes: props.attributes
    }));
  }
});

},{}],3:[function(require,module,exports){
"use strict";

var registerBlockType = wp.blocks.registerBlockType;
var _wp = wp,
    ServerSideRender = _wp.serverSideRender;
var createElement = wp.element.createElement;
wp.blocks.registerBlockType('ctcl-election-website/numbered-section-block', {
  title: 'Numbered Section',
  icon: 'editor-ol',
  category: 'election-blocks',
  edit: function edit(props) {
    return createElement('section', {
      className: 'numbered-section-block-editor'
    }, createElement('div', {
      className: 'numbered-section-block-editor-counter'
    }), createElement('div', {
      className: 'numbered-section-block-editor-content'
    }, createElement(wp.blockEditor.InnerBlocks)));
  },
  save: function save(props) {
    return createElement('section', {
      className: 'numbered-section'
    }, createElement('div', {
      className: 'numbered-section-content'
    }, createElement(wp.blockEditor.InnerBlocks.Content)));
  }
});

},{}],4:[function(require,module,exports){
"use strict";

var registerBlockType = wp.blocks.registerBlockType;
var _wp = wp,
    ServerSideRender = _wp.serverSideRender;
registerBlockType('ctcl-election-website/office-info', {
  title: 'Office Info',
  icon: 'building',
  category: 'election-blocks',
  edit: function edit(props) {
    return /*#__PURE__*/React.createElement(ServerSideRender, {
      block: "ctcl-election-website/office-info",
      attributes: props.attributes
    });
  }
});

},{}],5:[function(require,module,exports){
"use strict";

var registerBlockType = wp.blocks.registerBlockType;
var _wp = wp,
    ServerSideRender = _wp.serverSideRender;
var createElement = wp.element.createElement;
var PARENT_BLOCK = 'ctcl-election-website/tile-nav-section-block';
var CHILD_BLOCK = 'ctcl-election-website/tile-nav-block';
registerBlockType(PARENT_BLOCK, {
  title: 'Tile Navigation',
  icon: 'screenoptions',
  category: 'election-blocks',
  edit: function edit(props) {
    return createElement('div', {
      className: 'tile-nav-section-block-editor'
    }, createElement(wp.blockEditor.InnerBlocks, {
      allowedBlocks: [CHILD_BLOCK]
    }));
  },
  save: function save(props) {
    return createElement('nav', {
      className: 'tile-wrapper'
    }, createElement(wp.blockEditor.InnerBlocks.Content));
  }
});
registerBlockType(CHILD_BLOCK, {
  title: 'Tile',
  icon: 'screenoptions',
  category: 'election-blocks',
  parent: [PARENT_BLOCK],
  attributes: {
    icon: {
      type: 'string'
    },
    label: {
      type: 'string',
      default: ''
    },
    url: {
      type: 'string'
    }
  },
  edit: function edit(props) {
    function updateLabel(value) {
      props.setAttributes({
        label: value
      });
    }

    function updateLink(url, post) {
      props.setAttributes({
        url: url
      });

      if (post) {
        props.setAttributes({
          label: post.title
        });
      }
    }

    function updateIcon(value) {
      props.setAttributes({
        icon: value
      });
    }

    return createElement('div', {
      className: 'tile-nav-block-editor'
    }, createElement(wp.blockEditor.URLInput, {
      onChange: updateLink,
      value: props.attributes.url,
      label: 'Link'
    }), createElement(wp.components.TextControl, {
      label: 'Label',
      placeholder: 'Enter label',
      onChange: updateLabel,
      value: props.attributes.label
    }), createElement(wp.components.SelectControl, {
      onChange: updateIcon,
      options: [{
        value: 'registration',
        label: 'registration'
      }, {
        value: 'vote-by-mail',
        label: 'vote-by-mail'
      }, {
        value: 'view-election-results',
        label: 'view-election-results'
      }, {
        value: 'whats-on-ballot',
        label: 'whats-on-ballot'
      }, {
        value: 'where-to-vote',
        label: 'where-to-vote'
      }, {
        value: 'become-poll-worker',
        label: 'become-poll-worker'
      }, {
        value: 'campaign-resources',
        label: 'campaign-resources'
      }, {
        value: 'news',
        label: 'news'
      }],
      label: 'Icon',
      value: props.attributes.icon
    }));
  },
  save: function save(props) {
    return createElement('a', {
      className: 'tile',
      href: props.attributes.url
    }, createElement('div', {
      className: 'bounding-box',
      id: props.attributes.icon
    }), createElement('span', null, props.attributes.label));
  }
});

},{}]},{},[1,2,3,4,5]);
