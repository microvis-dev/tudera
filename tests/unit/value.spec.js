import {
    test,
    describe,
    expect,
    beforeEach
} from "vitest";
import {
    mount
} from "@vue/test-utils";
import Value from "@/resources/js/Pages/WorkspaceTable/Components/Value.vue";

describe("Value component unit test", () => {
    const value = {
        created_at: null,
        updated_at: null,
        column_id: 7,
        id: 1,
        order: 1,
        value: "Hello Test"
    }
    const statusValue = {
        created_at: null,
        updated_at: null,
        column_id: 12,
        id: 2,
        order: 1,
        value: "done"
    }
    const column = {
        created_at: null,
        updated_at: null,
        id: 12,
        table_id: "aa24da9e-e817-4726-aefc-ccc29cfdb5d6",
        name: "Test Status Column",
        order: 1,
        type: "status"
    }

    const statusOptions = [{
        created_at: null,
        updated_at: null,
        id: 10,
        column_id: 12,
        value: "done"
    }, {
        created_at: null,
        updated_at: null,
        id: 11,
        column_id: 12,
        value: "in-progress"
    }]

    let wrapper

    beforeEach(async () => {
        wrapper = mount(Value, {
            props: {
                value: statusValue,
                column: column,
                options: statusOptions
            }
        })
        await wrapper.vm.$nextTick()
    })

    test("Status value shows right test", async () => {
        var valueSpan = wrapper.find('#valueSpan')

        expect(valueSpan.text()).toBe(statusValue.value)
    })

    test("Status value select and change test", async () => {
        await wrapper.setProps({
            value: statusValue,
            column: column,
            options: statusOptions
        })
        await wrapper.vm.$nextTick()

        var valueEditor = wrapper.find("#valueEditor")
        await valueEditor.trigger('dblclick')
        await wrapper.vm.$nextTick()

        var statusSelect = wrapper.find("#statusSelect")
        expect(statusSelect.exists()).toBeTruthy()

        await statusSelect.setValue("in-progress")
        await statusSelect.trigger("change")
        await wrapper.vm.$nextTick()

        expect(wrapper.emitted()).toHaveProperty("update")

        expect(wrapper.emitted()["update"][0]).toEqual([
            "in-progress",
            statusValue
        ])
    })

    test("Non status value shows right test", async () => {
        await wrapper.setProps({
            value: value
        })

        var valueSpan = wrapper.find("#valueSpan")

        expect(valueSpan.text()).toBe(value.value)
    })

})
