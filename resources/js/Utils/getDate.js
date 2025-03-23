export default function getDate(date) {
    const adjustedDate = new Date(date);
    adjustedDate.setHours(adjustedDate.getHours() - 1);
    adjustedDate.setMonth(adjustedDate.getMonth());
    return adjustedDate;
}