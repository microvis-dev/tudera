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