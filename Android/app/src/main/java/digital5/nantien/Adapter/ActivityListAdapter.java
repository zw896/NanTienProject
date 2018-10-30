package digital5.nantien.Adapter;


import android.content.Context;
import android.support.annotation.LayoutRes;
import android.support.annotation.NonNull;
import android.support.annotation.Nullable;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.TextView;

import java.util.List;

import digital5.nantien.Models.EventItem;
import digital5.nantien.R;

public class ActivityListAdapter extends ArrayAdapter<EventItem> {

    List<EventItem> mDataItems;
    LayoutInflater mInflater;


    public ActivityListAdapter(@NonNull Context context, @NonNull List<EventItem> objects) {
        super(context, R.layout.activity_items, objects);

        mDataItems = objects;
        mInflater = LayoutInflater.from(context);

    }

    @NonNull
    @Override
    public View getView(int position, @Nullable View convertView, @NonNull ViewGroup parent) {

        if (convertView == null) {
            convertView = mInflater.inflate(R.layout.activity_items, parent, false);
        }

        TextView activityTitle = (TextView)convertView.findViewById(R.id.activity_titleText);

        EventItem item = mDataItems.get(position);
        activityTitle.setText(item.getItemID());


        return convertView;
    }
}
