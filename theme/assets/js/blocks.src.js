(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
"use strict";

var registerBlockType = wp.blocks.registerBlockType;
var _wp = wp,
    ServerSideRender = _wp.serverSideRender;
registerBlockType('ctcl-election-website/contact-form', {
  title: 'Contact Form',
  icon: 'email',
  category: 'election-blocks',
  edit: function edit(props) {
    return /*#__PURE__*/React.createElement(ServerSideRender, {
      block: "ctcl-election-website/contact-form",
      attributes: props.attributes
    });
  }
});

},{}],2:[function(require,module,exports){
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

},{}],3:[function(require,module,exports){
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

},{}],4:[function(require,module,exports){
"use strict";

var registerBlockType = wp.blocks.registerBlockType;
var _wp = wp,
    ServerSideRender = _wp.serverSideRender;
var createElement = wp.element.createElement;
registerBlockType('ctcl-election-website/tile-nav-section-block', {
  title: 'Tile Navigation Section',
  icon: 'screenoptions',
  category: 'election-blocks',
  edit: function edit(props) {
    return createElement('div', {
      className: 'tile-nav-section-block-editor'
    }, createElement(wp.blockEditor.InnerBlocks, {
      allowedBlocks: ['ctcl-election-website/tile-nav-block']
    }));
  },
  save: function save(props) {
    return createElement('nav', {
      className: 'tile-wrapper'
    }, createElement(wp.blockEditor.InnerBlocks.Content));
  }
});
registerBlockType('ctcl-election-website/tile-nav-block', {
  title: 'Tile Navigation',
  icon: 'screenoptions',
  category: 'election-blocks',
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

},{}]},{},[1,2,3,4]);
