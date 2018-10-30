package digital5.nantien;

import android.content.Intent;
import android.support.annotation.NonNull;
import android.support.design.widget.NavigationView;
import android.support.v4.view.GravityCompat;
import android.support.v4.widget.DrawerLayout;
import android.support.v7.app.ActionBarDrawerToggle;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.support.v7.widget.Toolbar;
import android.view.MenuItem;
import android.widget.ListView;
import android.widget.Toast;

import java.util.ArrayList;
import java.util.List;

import digital5.nantien.Activity.MainActivity;
import digital5.nantien.Adapter.ActivityListAdapter;
import digital5.nantien.Adapter.ReflectionListAdapter;
import digital5.nantien.Models.EventItem;

public class reflections_list extends MainActivity {


    //holding activity items
    private static List<EventItem> dataItemList;

    static {
        dataItemList= new ArrayList<>();

        for(int i = 0; i < 15; i++){
            addItem(new EventItem("Reflection :"+i, "Sample Name", "Sample Description"));
        }
    }

    public static void addItem(EventItem item){
        dataItemList.add(item);
    }

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        getLayoutInflater().inflate(R.layout.activity_reflections_list, frameLayout);

        toolbar.setTitle("Reflections");


        ReflectionListAdapter arrayAdapter = new ReflectionListAdapter(this, dataItemList);
        ListView list = (ListView) findViewById(R.id.reflections_list);
        list.setAdapter(arrayAdapter);


    }


    @Override
    protected void onResume() {
        super.onResume();
        // to check current activity in the navigation drawer
        navigationView.getMenu().getItem(2).setChecked(true);
    }

}
