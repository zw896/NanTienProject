package digital5.nantien.Models;

// Used to store the data of an event gotten from the json api data
// Inherits from EventItem class
public class EventListItem  extends EventItem {

    // Members
    private int sticky;

    public EventListItem() {

    }

    public EventListItem(String itemID, String itemName, String description, int sticky) {
        super.setItemID(itemID);
        super.setItemName(itemName);
        super.setDescription(description);
        this.sticky = sticky;
    }

    // Get
    public int getSticky() { return sticky; }

    // Set
    public void setSticky(int sticky) { this.sticky = sticky; }

}
