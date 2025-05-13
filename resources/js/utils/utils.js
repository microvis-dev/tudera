export function getDate(date) {
    const adjustedDate = new Date(date);
    adjustedDate.setHours(adjustedDate.getHours());
    adjustedDate.setMonth(adjustedDate.getMonth());

    return adjustedDate;
}

export function getWorkspaceEventDate(date) {
    const adjustedDate = getDate(date)

    adjustedDate.setHours(adjustedDate.getHours() + 2);
    return adjustedDate;
}

export function formatTimeAgo(dateInput) {
    if (!dateInput) return 'Never updated';

    const date = new Date(dateInput);
    if (isNaN(date.getTime())) {
        return 'Invalid date'; 
    }

    const now = new Date();
    const diffMs = now - date;

    const diffSec = Math.floor(diffMs / 1000);
    const diffMin = Math.floor(diffSec / 60);
    const diffHour = Math.floor(diffMin / 60);
    const diffDay = Math.floor(diffHour / 24);

    if (diffDay > 0) {
        return `${diffDay} ${diffDay === 1 ? 'day' : 'days'} ago`;
    } else if (diffHour > 0) {
        return `${diffHour} ${diffHour === 1 ? 'hour' : 'hours'} ago`;
    } else if (diffMin > 0) {
        return `${diffMin} ${diffMin === 1 ? 'minute' : 'minutes'} ago`;
    } else if (diffSec >= 0) { // Check for non-negative seconds for "just now"
        return 'Just now';
    } else {
        return 'In the future'; // Or handle future dates as needed
    }
}

export function getInputType(type) {
    switch (type) {
        case "integer":
            return "number"
        case "float":
            return "number"
        case "datetime":
            return "datetime-local"
        default:
            "text"
    }
}

export function getLatestDate(dates) {
    let latestDate = dates.sort((d1, d2) => new Date(d1) - new Date(d2))
    
    return new Date(latestDate[0])
}

export function formatDate(date) {
    return new Date(date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' }) + '.' 
}
