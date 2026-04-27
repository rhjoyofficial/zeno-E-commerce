class NotificationSystem {
    static show(options) {
        const {
            type = 'success',
            title,
            message,
            product = null,
            duration = 5000,
            showProgress = true,
            showCloseButton = true,
            action = null,
        } = options;

        const container = document.getElementById('notification-container');
        const notification = document.createElement('div');
        const notificationId = 'notification-' + Date.now();

        const typeClasses = {
            success: 'bg-green-50 border-green-500 text-green-800',
            error: 'bg-red-50 border-red-500 text-red-800',
            warning: 'bg-yellow-50 border-yellow-500 text-yellow-800',
            info: 'bg-blue-50 border-blue-500 text-blue-800',
        };

        notification.id = notificationId;
        notification.className = `relative rounded-lg shadow-lg border-l-4 overflow-hidden ${typeClasses[type]} notification-enter`;

        let productHtml = '';
        if (product) {
            productHtml = `
                <div class="flex items-start mt-2">
                    <img src="${product.image || '/images/placeholder-product.png'}"
                        class="h-14 w-14 object-cover rounded border border-gray-200"
                        alt="${product.name}">
                    <div class="ml-3">
                        <p class="text-sm font-medium line-clamp-1">${product.name}</p>
                        ${product.price ? `<p class="text-sm text-gray-600 mt-1">$${product.price.toFixed(2)}</p>` : ''}
                        ${product.qty ? `<p class="text-xs text-gray-500 mt-1">Qty: ${product.qty}</p>` : ''}
                    </div>
                </div>`;
        }

        let actionHtml = '';
        if (action) {
            actionHtml = `
                <button onclick="${action.onclick}; document.getElementById('${notificationId}').remove()"
                    class="mt-2 w-full py-1 px-3 rounded text-sm font-medium ${type === 'success' ? 'bg-green-100 text-green-700 hover:bg-green-200' : 'bg-red-100 text-red-700 hover:bg-red-200'}">
                    ${action.text}
                </button>`;
        }

        notification.innerHTML = `
            <div class="p-4">
                <div class="flex justify-between items-start">
                    <div>
                        <h4 class="text-sm font-semibold">${title}</h4>
                        ${message ? `<p class="text-sm mt-1">${message}</p>` : ''}
                    </div>
                    ${showCloseButton ? `
                        <button onclick="document.getElementById('${notificationId}').classList.add('notification-exit');
                            setTimeout(() => document.getElementById('${notificationId}').remove(), 300)"
                            class="text-gray-400 hover:text-gray-500 focus:outline-none">
                            <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </button>` : ''}
                </div>
                ${productHtml}
                ${actionHtml}
            </div>
            ${showProgress ? `
                <div class="h-1 bg-gray-200 w-full">
                    <div class="h-full ${type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-yellow-500'} progress-bar"
                        style="animation-duration: ${duration}ms"></div>
                </div>` : ''}`;

        container.appendChild(notification);

        if (duration > 0) {
            setTimeout(() => {
                notification.classList.add('notification-exit');
                setTimeout(() => notification.remove(), 300);
            }, duration);
        }

        return notificationId;
    }
}

window.NotificationSystem = NotificationSystem;
