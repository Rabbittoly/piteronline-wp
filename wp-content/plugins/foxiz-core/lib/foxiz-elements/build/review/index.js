(()=>{"use strict";var e={n:t=>{var l=t&&t.__esModule?()=>t.default:()=>t;return e.d(l,{a:l}),l},d:(t,l)=>{for(var a in l)e.o(l,a)&&!e.o(t,a)&&Object.defineProperty(t,a,{enumerable:!0,get:l[a]})},o:(e,t)=>Object.prototype.hasOwnProperty.call(e,t)};const t=window.wp.blocks,l=JSON.parse('{"u2":"foxiz-elements/review"}'),a=window.wp.element,n=window.wp.i18n,o=window.wp.components,r=window.wp.blockEditor,i=window.wp.serverSideRender;var c=e.n(i);(0,t.registerBlockType)(l.u2,{edit:function(e){const{attributes:t,setAttributes:l}=e,{heading:i,headingHTMLTag:s,tocAdded:d,headingColor:m,darkHeadingColor:u,desktopHeadingSize:_,tabletHeadingSize:p,mobileHeadingSize:b,description:g,descriptionColor:C,darkDescriptionColor:h,desktopDescriptionSize:E,tabletDescriptionSize:v,mobileDescriptionSize:k,metaScore:B,meta:y,overlayMetaColor:S,overlayMetaBg:D,price:P,salePrice:T,priceCurrency:x,offerUntil:w,priceColor:f,darkPriceColor:z,desktopPriceSize:N,tabletPriceSize:M,mobilePriceSize:O,image:L,imageURL:A,imageAlt:H,productFeatures:I,prosLabel:R,productPros:F,consLabel:U,productCons:V,buyButtons:$,buttonColor:j,buttonBg:W,darkButtonColor:q,darkButtonBg:G,starColor:J,isBorderButtonColor:K,isBorderButtonBorder:Q,isBorderDarkButtonColor:X,isBorderDarkButtonBg:Y,desktopButtonSize:Z,tabletButtonSize:ee,mobileButtonSize:te,shadow:le,borderStyle:ae,borderColor:ne,darkBorderColor:oe,borderWidth:re,borderRadius:ie,background:ce,darkBackground:se,desktopPadding:de,tabletPadding:me,mobilePadding:ue,isMainReview:_e,overlayLink:pe,overlayLinkInternal:be,overlayLinkSponsored:ge}=t,Ce=(e,t)=>{l({[e]:t})},he=(e,a,n,o)=>{const r=[...t[e]];r[a]={...r[a],[n]:o},l({[e]:r})};function Ee(e,a){const n=[...t[e]];n.push(a),l({[e]:n})}const ve=(e,a)=>{const n=[...t[e]];n.splice(a,1),l({[e]:n})},ke=[{label:(0,n.__)("None"),value:"none"},{label:(0,n.__)("Solid"),value:"solid"},{label:(0,n.__)("Dashed"),value:"dashed"},{label:(0,n.__)("Dotted"),value:"dotted"},{label:(0,n.__)("Double"),value:"double"}],Be=[{label:(0,n.__)("H2"),value:"h2"},{label:(0,n.__)("H3"),value:"h3"},{label:(0,n.__)("H4"),value:"h4"},{label:(0,n.__)("H5"),value:"h5"},{label:(0,n.__)("H6"),value:"h6"},{label:(0,n.__)("p"),value:"p"},{label:(0,n.__)("Div"),value:"div"}];return(0,a.createElement)("div",(0,r.useBlockProps)(),(0,a.createElement)(r.InspectorControls,null,(0,a.createElement)(o.PanelBody,{title:(0,n.__)("Product Image"),initialOpen:!0},(0,a.createElement)(o.BaseControl,null,(0,a.createElement)(r.MediaUploadCheck,null,(0,a.createElement)(r.MediaUpload,{onSelect:e=>function(e,t){l({[e]:t.url}),l({[e+"Alt"]:t.alt})}("image",e),allowedTypes:["image"],value:L,render:({open:e})=>(0,a.createElement)("div",null,!L&&(0,a.createElement)(o.Button,{className:"button button-large",onClick:e},(0,n.__)("Add Image")),L&&(0,a.createElement)("div",null,(0,a.createElement)("img",{src:L,alt:"image"}),(0,a.createElement)(o.Button,{className:"button button-large",onClick:()=>(l({["image"]:""}),void l({imageAlt:""}))},(0,n.__)("Remove Image"))))}))),(0,a.createElement)(o.TextareaControl,{label:(0,n.__)("or Enter Custom Link"),value:A,onChange:e=>Ce("imageURL",e),placeholder:(0,n.__)("//website.com/.../image.jpg")})),(0,a.createElement)(o.PanelBody,{title:(0,n.__)("Details"),initialOpen:!0},(0,a.createElement)(o.TextareaControl,{label:(0,n.__)("Product Name"),value:i,onChange:e=>Ce("heading",e),placeholder:(0,n.__)("Input your product title...")}),(0,a.createElement)(o.SelectControl,{label:(0,n.__)("Heading Tag"),value:s,options:Be,onChange:e=>Ce("headingHTMLTag",e)}),(0,a.createElement)(o.ToggleControl,{label:(0,n.__)("Table of Content Included"),checked:d,onChange:e=>Ce("tocAdded",e)}),(0,a.createElement)(o.TextareaControl,{label:(0,n.__)("Summary Review"),value:g,onChange:e=>Ce("description",e),placeholder:(0,n.__)("Input your product description or summary review...")}),(0,a.createElement)(o.TextControl,{label:(0,n.__)("Meta"),placeholder:"Good",value:y,onChange:e=>Ce("meta",e),help:(0,n.__)("The meta label request product image has been added in order to work.")}),(0,a.createElement)(o.TextControl,{label:(0,n.__)("Fallback Average Score"),value:B,onChange:e=>Ce("metaScore",e),placeholder:"5",help:(0,n.__)("This value is shown as a fallback when no product criteria data exists, within the range of 0 to 5.")}),(0,a.createElement)(o.TextControl,{label:(0,n.__)("Price"),placeholder:"$99",value:P,onChange:e=>Ce("price",e),help:(0,n.__)("Including the currency symbol in your price.")}),(0,a.createElement)(o.TextControl,{label:(0,n.__)("Discounted Price"),placeholder:"$49",value:T,onChange:e=>Ce("salePrice",e)}),(0,a.createElement)(o.TextControl,{label:(0,n.__)("Advantage Label"),value:R,onChange:e=>Ce("prosLabel",e)}),(0,a.createElement)(o.TextControl,{label:(0,n.__)("Disadvantage Label"),value:U,onChange:e=>Ce("consLabel",e)}),(0,a.createElement)(o.TextControl,{label:(0,n.__)("Currency Code"),placeholder:"USD",value:x,onChange:e=>Ce("priceCurrency",e),help:(0,n.__)("This value for the schema markup. Currency code in three digit ISO 4217 code")}),(0,a.createElement)(o.TextControl,{label:(0,n.__)("Price Valid Until"),placeholder:"yyyy-mm-dd",value:w,onChange:e=>Ce("offerUntil",e),help:(0,n.__)("Input the valid until date for this offer, Ensure you input right format: yyyy-mm-dd")})),(0,a.createElement)(o.PanelBody,{title:(0,n.__)("Criteria Data"),initialOpen:!1},I.map(((e,t)=>(0,a.createElement)(o.PanelBody,{key:t,title:e.label||(0,n.__)("+ Criteria..."),initialOpen:!1,className:"rb-repeated-settings"},(0,a.createElement)(o.TextControl,{label:(0,n.__)("Label"),value:e.label,onChange:e=>he("productFeatures",t,"label",e)}),(0,a.createElement)(o.RangeControl,{label:(0,n.__)("Rating"),value:e.rating,onChange:e=>he("productFeatures",t,"rating",e),initialPosition:5,min:0,step:.1,max:5}),(0,a.createElement)("div",{className:"rb-repeated-remove"},(0,a.createElement)(o.Button,{variant:"secondary",isSmall:!0,onClick:()=>ve("productFeatures",t)},(0,a.createElement)(o.Dashicon,{icon:"trash"})," ",(0,n.__)("Delete")))))),(0,a.createElement)("div",{className:"rb-repeated-add"},(0,a.createElement)(o.Button,{variant:"primary",onClick:()=>Ee("productFeatures",{label:"",rating:""})},(0,n.__)("Add New Criteria")))),(0,a.createElement)(o.PanelBody,{title:(0,n.__)("Advantages"),initialOpen:!1},F.map(((e,t)=>(0,a.createElement)(o.PanelBody,{key:t,title:`+ ${e.content}`||(0,n.__)("Please input pros..."),initialOpen:!1,className:"rb-repeated-settings"},(0,a.createElement)(o.TextareaControl,{label:(0,n.__)("Content"),value:e.content,onChange:e=>he("productPros",t,"content",e)}),(0,a.createElement)("div",{className:"rb-repeated-remove"},(0,a.createElement)(o.Button,{variant:"secondary",isSmall:!0,onClick:()=>ve("productPros",t)},(0,a.createElement)(o.Dashicon,{icon:"trash"})," ",(0,n.__)("Delete")))))),(0,a.createElement)("div",{className:"rb-repeated-add"},(0,a.createElement)(o.Button,{variant:"primary",onClick:()=>Ee("productPros",{content:""})},(0,n.__)("Add New")))),(0,a.createElement)(o.PanelBody,{title:(0,n.__)("Disadvantages"),initialOpen:!1},V.map(((e,t)=>(0,a.createElement)(o.PanelBody,{key:t,title:`- ${e.content}`||(0,n.__)("Please input cons..."),initialOpen:!1,className:"rb-repeated-settings"},(0,a.createElement)(o.TextareaControl,{label:(0,n.__)("Content"),value:e.content,onChange:e=>he("productCons",t,"content",e)}),(0,a.createElement)("div",{className:"rb-repeated-remove"},(0,a.createElement)(o.Button,{variant:"secondary",isSmall:!0,onClick:()=>ve("productCons",t)},(0,a.createElement)(o.Dashicon,{icon:"trash"})," ",(0,n.__)("Delete")))))),(0,a.createElement)("div",{className:"rb-repeated-add"},(0,a.createElement)(o.Button,{variant:"primary",onClick:()=>Ee("productCons",{content:""})},(0,n.__)("Add New")))),(0,a.createElement)(o.PanelBody,{title:(0,n.__)("Affiliate Links"),initialOpen:!1},$.map(((e,t)=>(0,a.createElement)(o.PanelBody,{key:t,title:e.label||(0,n.__)("Affiliate Link..."),initialOpen:!1,className:"rb-repeated-settings"},(0,a.createElement)(o.TextControl,{label:(0,n.__)("Label"),value:e.label,onChange:e=>he("buyButtons",t,"label",e),placeholder:"Buy on Amazon"}),(0,a.createElement)(o.TextControl,{label:(0,n.__)("Link"),value:e.link,onChange:e=>he("buyButtons",t,"link",e),placeholder:"https://..."}),(0,a.createElement)(o.ToggleControl,{label:(0,n.__)("Sponsored?"),checked:e.isSponsored,onChange:e=>he("buyButtons",t,"isSponsored",e)}),(0,a.createElement)(o.ToggleControl,{label:(0,n.__)("Border Style"),checked:e.isButtonBorder,onChange:e=>he("buyButtons",t,"isButtonBorder",e)}),(0,a.createElement)("div",{className:"rb-repeated-remove"},(0,a.createElement)(o.Button,{variant:"secondary",isSmall:!0,onClick:()=>ve("buyButtons",t)},(0,a.createElement)(o.Dashicon,{icon:"trash"})," ",(0,n.__)("Delete")))))),(0,a.createElement)("div",{className:"rb-repeated-add"},(0,a.createElement)(o.Button,{variant:"primary",onClick:()=>Ee("buyButtons",{label:"",link:"",isButtonBorder:!1,isSponsored:!1})},(0,n.__)("Add New Link")))),(0,a.createElement)(o.PanelBody,{title:(0,n.__)("Overlay Link"),initialOpen:!1},(0,a.createElement)(o.TextControl,{label:(0,n.__)("Block Overlay Link"),placeholder:(0,n.__)("https://..."),value:pe,onChange:e=>Ce("overlayLink",e),help:(0,n.__)("This link allows clicking the entire block.")}),(0,a.createElement)(o.ToggleControl,{label:(0,n.__)("Internal Link?"),checked:be,onChange:e=>Ce("overlayLinkInternal",e),help:(0,n.__)("Disable opening in a new tab and remove the nofollow,sponsored attributes in the rel for internal links.")}),(0,a.createElement)(o.ToggleControl,{label:(0,n.__)("Sponsored?"),checked:ge,onChange:e=>Ce("overlayLinkSponsored",e),help:(0,n.__)("Tell the search engine bot your relationship with the linked page.")})),(0,a.createElement)(o.PanelBody,{title:(0,n.__)("Is Post Review?"),initialOpen:!1},(0,a.createElement)(o.ToggleControl,{label:(0,n.__)("Set it as the main review"),help:(0,n.__)("Use this review as the main review of this post. Enabling this will make this post appear in the review filters."),checked:_e,onChange:e=>Ce("isMainReview",e)}))),(0,a.createElement)(r.InspectorControls,{group:"styles"},(0,a.createElement)(o.PanelBody,{title:(0,n.__)("Heading"),initialOpen:!1},(0,a.createElement)("label",{className:"rb-control-label"},(0,n.__)("Heading Size")),(0,a.createElement)("div",{className:"responsive-settings"},(0,a.createElement)("div",null,(0,a.createElement)("label",null,(0,a.createElement)(o.Dashicon,{icon:"desktop"}),(0,n.__)("Desktop")),(0,a.createElement)(o.TextControl,{type:"number",value:_,onChange:e=>Ce("desktopHeadingSize",e)})),(0,a.createElement)("div",null,(0,a.createElement)("label",null,(0,a.createElement)(o.Dashicon,{icon:"tablet"}),(0,n.__)("Tablet")),(0,a.createElement)(o.TextControl,{type:"number",value:p,onChange:e=>Ce("tabletHeadingSize",e)})),(0,a.createElement)("div",null,(0,a.createElement)("label",null,(0,a.createElement)(o.Dashicon,{icon:"smartphone"}),(0,n.__)("Mobile")),(0,a.createElement)(o.TextControl,{type:"number",value:b,onChange:e=>Ce("mobileHeadingSize",e)}))),(0,a.createElement)(r.PanelColorSettings,{title:(0,n.__)("Color"),colorSettings:[{value:m,onChange:e=>Ce("headingColor",e),label:(0,n.__)("Color")},{value:u,onChange:e=>Ce("darkHeadingColor",e),label:(0,n.__)("Dark Mode - Color")}],enableAlpha:!0})),(0,a.createElement)(o.PanelBody,{title:(0,n.__)("Description"),initialOpen:!1},(0,a.createElement)("label",{className:"rb-control-label"},(0,n.__)("Font Size")),(0,a.createElement)("div",{className:"responsive-settings"},(0,a.createElement)("div",null,(0,a.createElement)("label",null,(0,a.createElement)(o.Dashicon,{icon:"desktop"}),(0,n.__)("Desktop")),(0,a.createElement)(o.TextControl,{type:"number",value:E,onChange:e=>Ce("desktopDescriptionSize",e)})),(0,a.createElement)("div",null,(0,a.createElement)("label",null,(0,a.createElement)(o.Dashicon,{icon:"tablet"}),(0,n.__)("Tablet")),(0,a.createElement)(o.TextControl,{type:"number",value:v,onChange:e=>Ce("tabletDescriptionSize",e)})),(0,a.createElement)("div",null,(0,a.createElement)("label",null,(0,a.createElement)(o.Dashicon,{icon:"smartphone"}),(0,n.__)("Mobile")),(0,a.createElement)(o.TextControl,{type:"number",value:k,onChange:e=>Ce("mobileDescriptionSize",e)}))),(0,a.createElement)(r.PanelColorSettings,{title:(0,n.__)("Color"),colorSettings:[{value:C,onChange:e=>Ce("descriptionColor",e),label:(0,n.__)("Color")},{value:h,onChange:e=>Ce("darkDescriptionColor",e),label:(0,n.__)("Dark Mode - Color")}],enableAlpha:!0})),(0,a.createElement)(o.PanelBody,{title:(0,n.__)("Price"),initialOpen:!1},(0,a.createElement)("label",{className:"rb-control-label"},(0,n.__)("Font Size")),(0,a.createElement)("div",{className:"responsive-settings"},(0,a.createElement)("div",null,(0,a.createElement)("label",null,(0,a.createElement)(o.Dashicon,{icon:"desktop"}),(0,n.__)("Desktop")),(0,a.createElement)(o.TextControl,{type:"number",value:N,onChange:e=>Ce("desktopPriceSize",e)})),(0,a.createElement)("div",null,(0,a.createElement)("label",null,(0,a.createElement)(o.Dashicon,{icon:"tablet"}),(0,n.__)("Tablet")),(0,a.createElement)(o.TextControl,{type:"number",value:M,onChange:e=>Ce("tabletPriceSize",e)})),(0,a.createElement)("div",null,(0,a.createElement)("label",null,(0,a.createElement)(o.Dashicon,{icon:"smartphone"}),(0,n.__)("Mobile")),(0,a.createElement)(o.TextControl,{type:"number",value:O,onChange:e=>Ce("mobilePriceSize",e)}))),(0,a.createElement)(r.PanelColorSettings,{title:(0,n.__)("Color"),colorSettings:[{value:f,onChange:e=>Ce("priceColor",e),label:(0,n.__)("Color")},{value:z,onChange:e=>Ce("darkPriceColor",e),label:(0,n.__)("Dark Mode - Color")}],enableAlpha:!0})),(0,a.createElement)(o.PanelBody,{title:(0,n.__)("Review Meta"),initialOpen:!1},(0,a.createElement)(r.PanelColorSettings,{title:(0,n.__)("Color"),colorSettings:[{value:J,onChange:e=>Ce("starColor",e),label:(0,n.__)("Star Color")},{value:S,onChange:e=>Ce("overlayMetaColor",e),label:(0,n.__)("Overlay Meta - Color")},{value:D,onChange:e=>Ce("overlayMetaBg",e),label:(0,n.__)("Overlay Meta - Background")}],enableAlpha:!0})),(0,a.createElement)(o.PanelBody,{title:(0,n.__)("Affiliate Links"),initialOpen:!1},(0,a.createElement)("label",{className:"rb-control-label"},(0,n.__)("Font Size")),(0,a.createElement)("div",{className:"responsive-settings"},(0,a.createElement)("div",null,(0,a.createElement)("label",null,(0,a.createElement)(o.Dashicon,{icon:"desktop"}),(0,n.__)("Desktop")),(0,a.createElement)(o.TextControl,{type:"number",value:Z,onChange:e=>Ce("desktopButtonSize",e)})),(0,a.createElement)("div",null,(0,a.createElement)("label",null,(0,a.createElement)(o.Dashicon,{icon:"tablet"}),(0,n.__)("Tablet")),(0,a.createElement)(o.TextControl,{type:"number",value:ee,onChange:e=>Ce("tabletButtonSize",e)})),(0,a.createElement)("div",null,(0,a.createElement)("label",null,(0,a.createElement)(o.Dashicon,{icon:"smartphone"}),(0,n.__)("Mobile")),(0,a.createElement)(o.TextControl,{type:"number",value:te,onChange:e=>Ce("mobileButtonSize",e)}))),(0,a.createElement)(r.PanelColorSettings,{title:(0,n.__)("Background Button Style"),colorSettings:[{value:j,onChange:e=>Ce("buttonColor",e),label:(0,n.__)("Color")},{value:W,onChange:e=>Ce("buttonBg",e),label:(0,n.__)("Background")},{value:q,onChange:e=>Ce("darkButtonColor",e),label:(0,n.__)("Dark Mode - Color")},{value:G,onChange:e=>Ce("darkButtonBg",e),label:(0,n.__)("Dark Mode - Background")}],enableAlpha:!0}),(0,a.createElement)(r.PanelColorSettings,{title:(0,n.__)("Border Button Style"),colorSettings:[{value:K,onChange:e=>Ce("isBorderButtonColor",e),label:(0,n.__)("Color")},{value:Q,onChange:e=>Ce("isBorderButtonBorder",e),label:(0,n.__)("Border Color")},{value:X,onChange:e=>Ce("isBorderDarkButtonColor",e),label:(0,n.__)("Dark Mode - Color")},{value:Y,onChange:e=>Ce("isBorderDarkButtonBg",e),label:(0,n.__)("Dark Mode - Border Color")}],enableAlpha:!0})),(0,a.createElement)(o.PanelBody,{title:(0,n.__)("Border & Shadow"),initialOpen:!1},(0,a.createElement)(o.SelectControl,{label:(0,n.__)("Border Style"),value:ae,options:ke,onChange:e=>Ce("borderStyle",e),help:(0,n.__)("Please select the border color to see the border if you enable it.")}),ae&&"none"!==ae&&(0,a.createElement)(a.Fragment,null,(0,a.createElement)(o.__experimentalBoxControl,{resetValues:{top:null,right:null,bottom:null,left:null},label:(0,n.__)("Border Width"),values:re,onChange:e=>Ce("borderWidth",e)}),(0,a.createElement)(r.PanelColorSettings,{title:(0,n.__)("Border Color"),colorSettings:[{value:ne,onChange:e=>Ce("borderColor",e),label:(0,n.__)("Color")},{value:oe,onChange:e=>Ce("darkBorderColor",e),label:(0,n.__)("Dark Mode - Color")}]})),(0,a.createElement)(o.RangeControl,{label:(0,n.__)("Border Radius"),value:ie,onChange:e=>Ce("borderRadius",e),step:1,max:100,min:0}),(0,a.createElement)(o.ToggleControl,{label:(0,n.__)("Shadow"),checked:le,onChange:e=>Ce("shadow",e)}),(0,a.createElement)(r.PanelColorSettings,{title:(0,n.__)("Background"),colorSettings:[{value:ce,onChange:e=>Ce("background",e),label:(0,n.__)("Background")},{value:se,onChange:e=>Ce("darkBackground",e),label:(0,n.__)("Dark Mode - Background")}]})),(0,a.createElement)(o.PanelBody,{title:(0,n.__)("Inner Padding"),initialOpen:!1},(0,a.createElement)(o.BaseControl,null,(0,a.createElement)(o.Tip,null,(0,n.__)("These settings will apply to block wrapper. Click outside to see the update!"))),(0,a.createElement)("div",{className:"res-padding-control"},(0,a.createElement)(o.__experimentalBoxControl,{resetValues:{top:"30px",right:"30px",bottom:"30px",left:"30px"},label:(0,a.createElement)(a.Fragment,null,(0,a.createElement)(o.Dashicon,{icon:"desktop"})," ",(0,n.__)("Desktop")),values:de,onChange:e=>Ce("desktopPadding",e)}),(0,a.createElement)(o.__experimentalBoxControl,{resetValues:{top:"25px",right:"25px",bottom:"25px",left:"25px"},label:(0,a.createElement)(a.Fragment,null,(0,a.createElement)(o.Dashicon,{icon:"tablet"})," ",(0,n.__)("Tablet")),values:me,onChange:e=>Ce("tabletPadding",e)}),(0,a.createElement)(o.__experimentalBoxControl,{resetValues:{top:"20px",right:"20px",bottom:"20px",left:"20px"},label:(0,a.createElement)(a.Fragment,null,(0,a.createElement)(o.Dashicon,{icon:"smartphone"})," ",(0,n.__)("Mobile")),values:ue,onChange:e=>Ce("mobilePadding",e)})))),(0,a.createElement)(o.Disabled,null,(0,a.createElement)(c(),{block:"foxiz-elements/review",attributes:t})))},save:function(e){return null}})})();