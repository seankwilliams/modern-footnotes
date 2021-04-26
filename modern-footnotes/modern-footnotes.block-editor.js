/* Copyright 2017-2021 Sean Williams
    This file is part of Modern Footnotes.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License along
    with this program; if not, write to the Free Software Foundation, Inc.,
    51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
*/
( function( wp ) {
    var { __ } = wp.i18n;
    var ModernFootnotesButton = function( props ) {
        return wp.element.createElement(
            wp.editor.RichTextToolbarButton, {
                icon: wp.element.createElement('span', { 'className': 'modern-footnotes-admin-button' }),
                title: __('Add a Footnote', 'modern-footnotes'),
                onClick: function() {
                    props.onChange( wp.richText.toggleFormat(
                        props.value,
                        { type: 'modern-footnotes/footnote' }
                    ));
                },
                isActive: props.isActive,
            }
        );
    }
    wp.richText.registerFormatType(
        'modern-footnotes/footnote', {
            title: 'Modern Footnote',
            tagName: 'mfn',
            className: null,
            edit: ModernFootnotesButton
        }
    );
} )( window.wp );
