(function(wp) {
    const { createElement } = wp.element;
    const { createHigherOrderComponent } = wp.compose;

    const withDoubleSpaceHighlight = createHigherOrderComponent((BlockEdit) => {
        return (props) => {
            const { name, attributes } = props;

            if (name !== 'core/paragraph') {
                return createElement(BlockEdit, props);
            }

            // Учет обычных пробелов, неразрывных пробелов в формате HTML и Unicode
            const spaceRegex = /(\u00A0|&nbsp;)/g;
            const doubleSpaceRegex = new RegExp(`( {2})|( ${spaceRegex.source})|(${spaceRegex.source} )|(${spaceRegex.source}{2})`, 'g');
            const updatedContent = attributes.content.replace(doubleSpaceRegex, '<span class="double-space-highlight">$&</span>');

            const newProps = {
                ...props,
                attributes: {
                    ...attributes,
                    content: updatedContent
                }
            };

            return createElement(BlockEdit, newProps);
        };
    }, 'withDoubleSpaceHighlight');

    wp.hooks.addFilter('editor.BlockEdit', 'my-plugin/with-double-space-highlight', withDoubleSpaceHighlight);
})(window.wp);
