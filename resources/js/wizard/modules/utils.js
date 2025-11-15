// Shared helpers for the booking wizard UI

export const debounce = (func, wait = 300) => {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
};

export const showError = (message) => {
  if (window.Swal?.fire) {
    window.Swal.fire({
      icon: 'error',
      title: 'Oops...',
      text: message,
      confirmButtonColor: '#dc3545',
    });
  } else {
    window.alert(message);
  }
};

export const showWarning = (message) => {
  if (window.Swal?.fire) {
    window.Swal.fire({
      icon: 'warning',
      title: 'Notice',
      text: message,
      confirmButtonColor: '#ffc107',
    });
  } else {
    window.alert(message);
  }
};

export const showSuccess = (message) => {
  if (window.Swal?.fire) {
    window.Swal.fire({
      icon: 'success',
      title: 'Success!',
      text: message,
      confirmButtonColor: '#00C950',
    });
  } else {
    window.alert(message);
  }
};

export const formatTimeLabel = (value = '') => {
  if (!value) return '';
  const [hourStr, minStr] = value.split(':');
  if (hourStr === undefined || minStr === undefined) return value;
  let hour = Number(hourStr);
  const minutes = Number(minStr);
  const period = hour >= 12 ? 'PM' : 'AM';
  hour = hour % 12 || 12;
  return `${hour}:${minutes.toString().padStart(2, '0')} ${period}`;
};

export const formatDateLabel = (value = '') => {
  if (!value) return '';
  const [year, month, day] = value.split('-').map(Number);
  if ([year, month, day].some(Number.isNaN)) return value;
  const dateObj = new Date(year, month - 1, day);
  return dateObj.toLocaleDateString('en-US', {
    weekday: 'long',
    month: 'long',
    day: 'numeric',
    year: 'numeric',
  });
};

export const generateReferenceCode = () => {
  const randomSegment = Math.random().toString(36).toUpperCase().slice(2, 8);
  return `ENC-${randomSegment}`;
};
