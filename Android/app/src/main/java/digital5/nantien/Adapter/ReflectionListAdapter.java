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

public class ReflectionListAdapter extends ArrayAdapter<EventItem> {

    List<EventItem> mDataItems;
    LayoutInflater mInflater;


    public ReflectionListAdapter(@NonNull Context context, @NonNull List<EventItem> objects) {
        super(context, R.layout.activity_reflections_list, objects);

        mDataItems = objects;
        mInflater = LayoutInflater.from(context);

    }

    @NonNull
    @Override
    public View getView(int position, @Nullable View convertView, @NonNull ViewGroup parent) {

        if (convertView == null) {
            convertView = mInflater.inflate(R.layout.reflection_items, parent, false);
        }

        TextView reflectionTitle = (TextView)convertView.findViewById(R.id.reflection_titleText);

        EventItem item = mDataItems.get(position);
        reflectionTitle.setText(item.getItemID());


        return convertView;
    }
}
