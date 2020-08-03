(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && Symbol.iterator in Object(iter)) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

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
var DEFAULT_HEADER_TAG = 'h3';
var TEMPLATE = [[CHILD_BLOCK, {
  headerTag: DEFAULT_HEADER_TAG
}]];
var AccordionBlockContext = wp.element.createContext(DEFAULT_HEADER_TAG);

var hasIconTag = function hasIconTag(attributes) {
  var headerTag = attributes.headerTag;
  return 'h3' === headerTag;
};

var getIconEl = function getIconEl(attributes) {
  var icon = attributes.icon;

  if (hasIconTag(attributes) && icon) {
    var iconUrl = "".concat(blockEditorVars.baseUrl, "/").concat(icon, ".svg");
    return createElement('img', {
      width: 50,
      height: 50,
      src: iconUrl
    });
  }

  return null;
};

registerBlockType(CHILD_BLOCK, {
  title: 'Section',
  icon: 'book',
  category: 'election-blocks',
  parent: [PARENT_BLOCK],
  attributes: {
    headerTag: {
      type: 'string',
      default: DEFAULT_HEADER_TAG
    },
    heading: {
      type: 'string'
    },
    icon: {
      type: 'string'
    }
  },
  edit: function edit(props) {
    return /*#__PURE__*/React.createElement("div", {
      className: "accordion-section-editor"
    }, /*#__PURE__*/React.createElement(AccordionBlockContext.Consumer, null, function (value) {
      props.setAttributes({
        headerTag: value
      });
    }), hasIconTag(props.attributes) ? /*#__PURE__*/React.createElement(InspectorControls, null, /*#__PURE__*/React.createElement(PanelBody, {
      title: "Specify section settings",
      initialOpen: true
    }, /*#__PURE__*/React.createElement(PanelRow, null, /*#__PURE__*/React.createElement(SelectControl, {
      label: "Icon",
      value: props.attributes.icon,
      options: [{
        value: null,
        label: 'Select an Icon'
      }].concat(_toConsumableArray(blockEditorVars.iconOptions.map(function (option) {
        return {
          value: option,
          label: option
        };
      }))),
      onChange: function onChange(val) {
        return props.setAttributes({
          icon: val
        });
      }
    })))) : /*#__PURE__*/React.createElement(React.Fragment, null), getIconEl(props.attributes), /*#__PURE__*/React.createElement(RichText, {
      className: "accordion-section-header",
      tagName: props.attributes.headerTag,
      onChange: function onChange(val) {
        return props.setAttributes({
          heading: val
        });
      },
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
    }, getIconEl(props.attributes), createElement('span', null, props.attributes.heading)), /*#__PURE__*/React.createElement("section", {
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
        label: 'H2 headers',
        value: 'h2'
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

function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }

function _toConsumableArray(arr) { return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread(); }

function _nonIterableSpread() { throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _unsupportedIterableToArray(o, minLen) { if (!o) return; if (typeof o === "string") return _arrayLikeToArray(o, minLen); var n = Object.prototype.toString.call(o).slice(8, -1); if (n === "Object" && o.constructor) n = o.constructor.name; if (n === "Map" || n === "Set") return Array.from(o); if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen); }

function _iterableToArray(iter) { if (typeof Symbol !== "undefined" && Symbol.iterator in Object(iter)) return Array.from(iter); }

function _arrayWithoutHoles(arr) { if (Array.isArray(arr)) return _arrayLikeToArray(arr); }

function _arrayLikeToArray(arr, len) { if (len == null || len > arr.length) len = arr.length; for (var i = 0, arr2 = new Array(len); i < len; i++) { arr2[i] = arr[i]; } return arr2; }

var registerBlockType = wp.blocks.registerBlockType;
var createElement = wp.element.createElement;
var createHigherOrderComponent = wp.compose.createHigherOrderComponent;
var _wp$blockEditor = wp.blockEditor,
    InspectorControls = _wp$blockEditor.InspectorControls,
    InnerBlocks = _wp$blockEditor.InnerBlocks,
    RichText = _wp$blockEditor.RichText,
    URLInput = _wp$blockEditor.URLInput;
var _wp$components = wp.components,
    PanelBody = _wp$components.PanelBody,
    PanelRow = _wp$components.PanelRow,
    SelectControl = _wp$components.SelectControl,
    TextControl = _wp$components.TextControl;
var PARENT_BLOCK = 'ctcl-election-website/tile-nav-section-block';
var CHILD_BLOCK = 'ctcl-election-website/tile-nav-block';

var getIconEl = function getIconEl(attributes) {
  var icon = attributes.icon;

  if (icon) {
    var iconUrl = "".concat(blockEditorVars.baseUrl, "/").concat(icon, ".svg");
    return createElement('img', {
      height: 50,
      src: iconUrl
    });
  }

  return null;
};

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

    var _props$attributes = props.attributes,
        label = _props$attributes.label,
        icon = _props$attributes.icon;
    var isEmpty = !label && !icon;
    return /*#__PURE__*/React.createElement("div", null, /*#__PURE__*/React.createElement(InspectorControls, null, /*#__PURE__*/React.createElement("div", {
      className: "tile-nav-settings"
    }, /*#__PURE__*/React.createElement(PanelBody, {
      title: "Specify tile values",
      initialOpen: true
    }, /*#__PURE__*/React.createElement(PanelRow, null, /*#__PURE__*/React.createElement(TextControl, {
      label: "Label",
      placeholder: "Enter Label",
      onChange: updateLabel,
      value: props.attributes.label
    }), /*#__PURE__*/React.createElement(URLInput, {
      label: "Page",
      value: props.attributes.url,
      onChange: updateLink
    }), /*#__PURE__*/React.createElement(SelectControl, {
      label: "Icon",
      value: props.attributes.icon,
      options: [{
        value: null,
        label: 'Select an Icon'
      }].concat(_toConsumableArray(blockEditorVars.iconOptions.map(function (option) {
        return {
          value: option,
          label: option
        };
      }))),
      onChange: updateIcon
    }))))), /*#__PURE__*/React.createElement("div", {
      className: "tile-nav-block-editor"
    }, /*#__PURE__*/React.createElement("div", {
      className: "tile"
    }, isEmpty ? /*#__PURE__*/React.createElement("span", {
      className: "placeholder"
    }, "Set tile values in control panel to your right.") : [getIconEl(props.attributes), /*#__PURE__*/React.createElement("span", null, label)])));
  },
  save: function save(props) {
    return /*#__PURE__*/React.createElement("a", {
      className: "tile",
      href: props.attributes.url
    }, /*#__PURE__*/React.createElement("div", {
      className: "bounding-box",
      id: props.attributes.icon
    }), /*#__PURE__*/React.createElement("span", null, props.attributes.label));
  }
});
var withClientIdClassName = createHigherOrderComponent(function (BlockListBlock) {
  return function (props) {
    return /*#__PURE__*/React.createElement(BlockListBlock, _extends({}, props, {
      className: 'tile-nav-block-editor-wrapper'
    }));
  };
}, 'withClientIdClassName');
wp.hooks.addFilter('editor.BlockListBlock', PARENT_BLOCK, withClientIdClassName);

},{}]},{},[1,2,3,4,5]);
