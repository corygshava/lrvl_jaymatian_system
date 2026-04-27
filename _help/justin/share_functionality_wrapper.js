/**
 * Share API Wrapper
 * Utilities for sharing files, blobs, and text using the Web Share API
 */

/**
 * Share a blob with the native share dialog
 * @param {Blob} blob - The blob to share
 * @param {string} filename - The filename to use
 * @param {Object} options - Optional title and text
 * @returns {Promise<void>}
 */
async function shareBlob(blob, filename, options = {}) {
  const { title = 'Share', text = '' } = options;
  
  const file = new File([blob], filename, { type: blob.type });
  
  if (navigator.share && navigator.canShare({ files: [file] })) {
    try {
      await navigator.share({
        files: [file],
        title,
        text
      });
      console.log('Shared successfully');
    } catch (err) {
      if (err.name === 'AbortError') {
        console.log('Share cancelled');
      } else {
        console.error('Share failed:', err);
        // Fallback to download
        downloadBlob(blob, filename);
      }
    }
  } else {
    console.log('Web Share API not supported, falling back to download');
    downloadBlob(blob, filename);
  }
}

/**
 * Fetch a file from URL and share it
 * @param {string} url - The URL to fetch
 * @param {string} filename - The filename to use when sharing
 * @param {Object} options - Optional title and text
 * @returns {Promise<void>}
 */
async function shareFromUrl(url, filename, options = {}) {
  try {
    const response = await fetch(url);
    if (!response.ok) {
      throw new Error(`Failed to fetch: ${response.status} ${response.statusText}`);
    }
    const blob = await response.blob();
    await shareBlob(blob, filename, options);
  } catch (err) {
    console.error('Failed to fetch and share:', err);
    throw err;
  }
}

/**
 * Share text only (no files)
 * @param {Object} options - Share options
 * @param {string} options.title - Title of the share
 * @param {string} options.text - Text to share
 * @param {string} options.url - Optional URL to share
 * @returns {Promise<void>}
 */
async function shareText({ title = '', text = '', url = '' }) {
  if (!navigator.share) {
    console.log('Web Share API not supported');
    // Fallback: copy to clipboard
    if (navigator.clipboard && text) {
      try {
        await navigator.clipboard.writeText(url || text);
        console.log('Copied to clipboard as fallback');
      } catch (err) {
        console.error('Failed to copy to clipboard:', err);
      }
    }
    return;
  }
  
  try {
    await navigator.share({
      title,
      text,
      url
    });
    console.log('Shared successfully');
  } catch (err) {
    if (err.name === 'AbortError') {
      console.log('Share cancelled');
    } else {
      console.error('Share failed:', err);
    }
  }
}

/**
 * Fallback: Download a blob when sharing isn't supported
 * @param {Blob} blob - The blob to download
 * @param {string} filename - The filename to use
 */
function downloadBlob(blob, filename) {
  const url = URL.createObjectURL(blob);
  const a = document.createElement('a');
  a.href = url;
  a.download = filename;
  document.body.appendChild(a);
  a.click();
  document.body.removeChild(a);
  URL.revokeObjectURL(url);
}

/**
 * Check if the Web Share API is available for files
 * @returns {boolean}
 */
function canShareFiles() {
  return navigator.share && navigator.canShare !== undefined;
}

/**
 * Check if the Web Share API is available for text
 * @returns {boolean}
 */
function canShareText() {
  return !!navigator.share;
}

// Example usage:
/*
// Share a canvas as PNG
const canvas = document.getElementById('myCanvas');
canvas.toBlob((blob) => {
  shareBlob(blob, 'drawing.png', { 
    title: 'My Drawing', 
    text: 'Check out my artwork!' 
  });
}, 'image/png');

// Share an image from URL
await shareFromUrl('https://example.com/image.jpg', 'photo.jpg', {
  title: 'Cool Photo',
  text: 'Found this awesome photo'
});

// Share text only
await shareText({
  title: 'Interesting Article',
  text: 'Check this out!',
  url: 'https://example.com/article'
});

// Just share a URL
await shareText({
  url: 'https://example.com'
});
*/