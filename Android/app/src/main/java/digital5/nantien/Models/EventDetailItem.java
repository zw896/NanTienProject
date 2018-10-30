package digital5.nantien.Models;


public class EventDetailItem extends EventItem {

    // Members
    private String start_time;
    private String end_time;
    private String venue;
    private String poster_url;

    public EventDetailItem() {

    }

    public EventDetailItem(String itemID, String itemName, String description, String start_time,
                           String end_time, String venue, String poster_url) {
        super.setItemID(itemID);
        super.setItemName(itemName);
        super.setDescription(description);
        this.start_time = start_time;
        this.end_time = end_time;
        this.venue = venue;
        this.poster_url = poster_url;
    }

    // Get
    public String getStartTime() { return start_time; }
    public String getEndTime() { return end_time; }
    public String getVenue() { return venue; }
    public String getPosterUrl() { return poster_url; }

    // Set
    public void setStartTime(String start_time) { this.start_time = start_time; }
    public void setEndTime(String end_time) { this.end_time = end_time; }
    public void setVenue(String venue) { this.venue = venue; }
    public void setPosterUrl(String poster_url) { this.poster_url = poster_url; }

}
