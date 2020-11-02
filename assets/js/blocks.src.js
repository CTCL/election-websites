(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

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
var NESTED_PARENT_BLOCK = 'ctcl-election-website/accordion-nested-group-block';
var AccordionBlockContext = wp.element.createContext(false);

var getIconEl = function getIconEl(_ref) {
  var icon = _ref.icon;

  if (icon) {
    var iconUrl = "".concat(blockEditorVars.baseUrl, "/").concat(icon, ".svg");
    return createElement('img', {
      height: 50,
      src: iconUrl
    });
  }

  return null;
};

var getHeaderTag = function getHeaderTag(_ref2) {
  var isNestedGroup = _ref2.isNestedGroup;
  return isNestedGroup ? 'h3' : 'h2';
};

var getHeaderClasses = function getHeaderClasses(_ref3) {
  var icon = _ref3.icon;
  return "accordion-section-header ".concat(icon ? 'with-icon' : '');
};

registerBlockType(CHILD_BLOCK, {
  title: 'Section',
  icon: 'book',
  category: 'election-blocks',
  parent: [PARENT_BLOCK],
  attributes: {
    heading: {
      type: 'string'
    },
    icon: {
      type: 'string'
    },
    isNestedGroup: {
      type: 'boolean',
      default: false
    }
  },
  edit: function edit(props) {
    var DISALLOWED_BLOCKS = [PARENT_BLOCK, CHILD_BLOCK];

    if (props.attributes.isNestedGroup) {
      DISALLOWED_BLOCKS.push(NESTED_PARENT_BLOCK);
    }

    var ALLOWED_BLOCKS = wp.blocks.getBlockTypes().map(function (block) {
      return block.name;
    }).filter(function (blockName) {
      return !DISALLOWED_BLOCKS.includes(blockName);
    });
    return /*#__PURE__*/React.createElement("div", {
      className: "accordion-section-editor"
    }, /*#__PURE__*/React.createElement(AccordionBlockContext.Consumer, null, function (value) {
      props.setAttributes({
        isNestedGroup: value
      });
    }), !props.attributes.isNestedGroup ? /*#__PURE__*/React.createElement(InspectorControls, null, /*#__PURE__*/React.createElement(PanelBody, {
      title: "Section",
      initialOpen: true
    }, /*#__PURE__*/React.createElement(PanelRow, null, /*#__PURE__*/React.createElement(SelectControl, {
      label: "Icon",
      value: props.attributes.icon,
      options: [{
        value: null,
        label: 'Select an Icon',
        key: '_placeholder'
      }].concat(_toConsumableArray(Object.entries(blockEditorVars.iconOptions).map(function (_ref4) {
        var _ref5 = _slicedToArray(_ref4, 2),
            value = _ref5[0],
            label = _ref5[1];

        return {
          value: value,
          label: label,
          key: value
        };
      }))),
      onChange: function onChange(val) {
        return props.setAttributes({
          icon: val
        });
      }
    })))) : /*#__PURE__*/React.createElement(React.Fragment, null), /*#__PURE__*/React.createElement("div", {
      className: "header-wrapper"
    }, getIconEl(props.attributes), /*#__PURE__*/React.createElement(RichText, {
      className: getHeaderClasses(props.attributes),
      tagName: getHeaderTag(props.attributes),
      onChange: function onChange(val) {
        return props.setAttributes({
          heading: val
        });
      },
      value: props.attributes.heading,
      placeholder: "Enter header here\u2026"
    })), /*#__PURE__*/React.createElement(InnerBlocks, {
      className: "accordion-section-content-editor",
      allowedBlocks: ALLOWED_BLOCKS
    }));
  },
  save: function save(props) {
    return /*#__PURE__*/React.createElement("div", {
      className: "accordion-section-wrapper ".concat(props.attributes.isNestedGroup ? 'subsection' : '')
    }, createElement(getHeaderTag(props.attributes), {
      className: getHeaderClasses(props.attributes)
    }, getIconEl(props.attributes), createElement('span', null, props.attributes.heading)), /*#__PURE__*/React.createElement("section", {
      className: "accordion-section-content"
    }, /*#__PURE__*/React.createElement(InnerBlocks.Content, null)));
  }
});

var getParentEditTemplate = function getParentEditTemplate(isNestedGroup) {
  return /*#__PURE__*/React.createElement("div", {
    className: "accordion-group-editor ".concat(isNestedGroup ? 'subsection' : '')
  }, /*#__PURE__*/React.createElement(AccordionBlockContext.Provider, {
    value: isNestedGroup
  }, /*#__PURE__*/React.createElement(InnerBlocks, {
    className: "accordion-group-wrapper",
    allowedBlocks: [CHILD_BLOCK],
    template: [[CHILD_BLOCK]]
  })));
};

registerBlockType(PARENT_BLOCK, {
  title: 'Collapsible Group',
  icon: 'book',
  category: 'election-blocks',
  edit: function edit() {
    return getParentEditTemplate(false);
  },
  save: function save(props) {
    return /*#__PURE__*/React.createElement("section", {
      className: "accordion-group"
    }, /*#__PURE__*/React.createElement(InnerBlocks.Content, null));
  }
});
registerBlockType(NESTED_PARENT_BLOCK, {
  title: 'Inner Collapsible Group',
  icon: 'book',
  category: 'election-blocks',
  parent: [CHILD_BLOCK],
  edit: function edit() {
    return getParentEditTemplate(true);
  },
  save: function save(props) {
    return /*#__PURE__*/React.createElement("section", {
      className: "accordion-group subsection"
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

var _lodash = lodash,
    startCase = _lodash.startCase;
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
registerBlockType('ctcl-election-website/read-more-block', {
  title: 'Read More',
  icon: 'editor-insertmore',
  category: 'election-blocks',
  attributes: {
    preview: {
      type: 'array',
      source: 'children',
      selector: '.read-more-preview-content'
    },
    remaining: {
      type: 'array',
      source: 'children',
      selector: '.read-more-remaining'
    }
  },
  edit: function edit(props) {
    return /*#__PURE__*/React.createElement("div", {
      className: "read-more-block-editor"
    }, /*#__PURE__*/React.createElement(RichText, {
      className: "read-more-preview-content",
      onChange: function onChange(val) {
        return props.setAttributes({
          preview: val
        });
      },
      value: props.attributes.preview,
      placeholder: "Enter preview text here\u2026"
    }), /*#__PURE__*/React.createElement("div", {
      class: "read-more-divider"
    }, "Read More"), /*#__PURE__*/React.createElement(RichText, {
      className: "read-more-remaining",
      onChange: function onChange(val) {
        return props.setAttributes({
          remaining: val
        });
      },
      value: props.attributes.remaining,
      placeholder: "Enter full text here\u2026"
    }));
  },
  save: function save(props) {
    return /*#__PURE__*/React.createElement("div", {
      className: "read-more-block less"
    }, /*#__PURE__*/React.createElement("p", {
      className: "read-more-preview"
    }, /*#__PURE__*/React.createElement("span", {
      className: "read-more-preview-content"
    }, props.attributes.preview), /*#__PURE__*/React.createElement("span", null, "\xA0\xA0"), /*#__PURE__*/React.createElement("a", {
      className: "read-more-link"
    }, "Read More")), /*#__PURE__*/React.createElement("p", {
      className: "read-more-remaining"
    }, props.attributes.remaining), /*#__PURE__*/React.createElement("p", {
      className: "read-less-link-wrapper"
    }, /*#__PURE__*/React.createElement("a", {
      className: "read-less-link"
    }, "Read Less")));
  }
});

},{}],6:[function(require,module,exports){
"use strict";

function _extends() { _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; }; return _extends.apply(this, arguments); }

function _slicedToArray(arr, i) { return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest(); }

function _nonIterableRest() { throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method."); }

function _iterableToArrayLimit(arr, i) { if (typeof Symbol === "undefined" || !(Symbol.iterator in Object(arr))) return; var _arr = []; var _n = true; var _d = false; var _e = undefined; try { for (var _i = arr[Symbol.iterator](), _s; !(_n = (_s = _i.next()).done); _n = true) { _arr.push(_s.value); if (i && _arr.length === i) break; } } catch (err) { _d = true; _e = err; } finally { try { if (!_n && _i["return"] != null) _i["return"](); } finally { if (_d) throw _e; } } return _arr; }

function _arrayWithHoles(arr) { if (Array.isArray(arr)) return arr; }

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
    TextControl = _wp$components.TextControl,
    ExternalLink = _wp$components.ExternalLink;
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
    return createElement('div', {
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
      title: "Tile",
      initialOpen: true
    }, /*#__PURE__*/React.createElement(PanelRow, null, /*#__PURE__*/React.createElement(TextControl, {
      label: "Label",
      placeholder: "Enter Label",
      onChange: updateLabel,
      value: props.attributes.label
    })), /*#__PURE__*/React.createElement(PanelRow, null, /*#__PURE__*/React.createElement(URLInput, {
      label: "Link",
      value: props.attributes.url,
      onChange: updateLink
    })), /*#__PURE__*/React.createElement(PanelRow, null, /*#__PURE__*/React.createElement(SelectControl, {
      label: "Icon",
      value: props.attributes.icon,
      options: [{
        value: null,
        label: 'Select an Icon',
        key: '_placeholder'
      }].concat(_toConsumableArray(Object.entries(blockEditorVars.iconOptions).map(function (_ref) {
        var _ref2 = _slicedToArray(_ref, 2),
            value = _ref2[0],
            label = _ref2[1];

        return {
          value: value,
          label: label,
          key: value
        };
      }))),
      onChange: updateIcon
    }))), /*#__PURE__*/React.createElement(PanelBody, {
      title: "View Page",
      initialOpen: true
    }, /*#__PURE__*/React.createElement(PanelRow, null, /*#__PURE__*/React.createElement(ExternalLink, {
      href: props.attributes.url
    }, props.attributes.url))))), /*#__PURE__*/React.createElement("div", {
      className: "tile-nav-block-editor"
    }, /*#__PURE__*/React.createElement("div", {
      className: "tile"
    }, isEmpty ? /*#__PURE__*/React.createElement("span", {
      className: "placeholder"
    }, "Set tile values in control panel to your right.") : null, !isEmpty ? getIconEl(props.attributes) : null, !isEmpty ? /*#__PURE__*/React.createElement("label", null, label) : null)));
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

},{}]},{},[1,2,3,4,5,6]);
