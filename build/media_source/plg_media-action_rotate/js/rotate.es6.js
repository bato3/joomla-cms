/**
 * @copyright  Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */
Joomla = window.Joomla || {};

Joomla.MediaManager = Joomla.MediaManager || {};
Joomla.MediaManager.Edit = Joomla.MediaManager.Edit || {};

(() => {
  'use strict';

  // Update image
  const rotate = (angle) => {
    // The image element
    const image = document.getElementById('image-source');

    // The canvas where we will resize the image
    const canvas = document.createElement('canvas');

    // Pseudo rectangle calculation
    if ((angle >= 0 && angle < 45)
      || (angle >= 135 && angle < 225)
      || (angle >= 315 && angle <= 360)) {
      canvas.width = image.width;
      canvas.height = image.height;
    } else {
      // swap
      canvas.width = image.height;
      canvas.height = image.width;
    }
    const ctx = canvas.getContext('2d');
    ctx.translate(canvas.width / 2, canvas.height / 2);
    ctx.rotate((angle * Math.PI) / 180);
    ctx.drawImage(image, -image.width / 2, -image.height / 2);

    // The format
    const format = Joomla.MediaManager.Edit.original.extension === 'jpg' ? 'jpeg' : 'jpg';

    // The quality
    const quality = document.getElementById('jform_rotate_quality').value;

    // Creating the data from the canvas
    Joomla.MediaManager.Edit.current.contents = canvas.toDataURL(`image/${format}`, quality);

    // Updating the preview element
    const preview = document.getElementById('image-preview');
    preview.width = canvas.width;
    preview.height = canvas.height;
    preview.src = Joomla.MediaManager.Edit.current.contents;

    // Update the height input box
    document.getElementById('jform_rotate_a').value = angle;

    // Notify the app that a change has been made
    window.dispatchEvent(new Event('mediaManager.history.point'));
  };

  const initRotate = () => {
    const funct = () => {
      // The number input listener
      document.getElementById('jform_rotate_a').addEventListener('input', ({ target }) => {
        rotate(parseInt(target.value, 10));

        // Deselect all buttons
        const elements = [].slice.call(document.querySelectorAll('#jform_rotate_distinct label'));
        elements.forEach((element) => {
          element.classList.remove('active');
          element.classList.remove('focus');
        });
      });

      // The 90 degree rotate buttons listeners
      const elements = [].slice.call(document.querySelectorAll('#jform_rotate_distinct label'));
      elements.forEach((element) => {
        element.addEventListener('click', ({ target }) => {
          rotate(parseInt(target.querySelector('input').value, 10));
        });
      });
    };
    setTimeout(funct, 1000);
  };

  // Register the Events
  Joomla.MediaManager.Edit.rotate = {
    Activate(mediaData) {
      // Initialize
      initRotate(mediaData);
    },
    Deactivate() {
    },
  };
})();
