import {
    test,
    describe,
    expect,
} from "vitest";
import {
    mount
} from "@vue/test-utils";
import EmptyValue from "@/resources/js/Pages/WorkspaceTable/Components/EmptyValue.vue";

describe("EmptyValue.vue", () => {
    let wrapper

    const stringColumn = {
        id: 1,
        type: "string",
        name: "Task Name"
    }
    const statusColumn = {
        id: 2,
        type: "status",
        name: "Progress"
    }
    const statusOptions = [{
            value: "None"
        },
        {
            value: "Todo"
        },
        {
            value: "In Progress"
        },
        {
            value: "Done"
        }
    ]
    const defaultOrder = 3

    const mountComponent = (props) => {
        return mount(EmptyValue, {
            props: {
                column: stringColumn,
                isNewRow: false,
                order: defaultOrder,
                options: [],
                ...props,
            },
        })
    }

    test("renders add button by default for non-status column and not new row", () => {
        wrapper = mountComponent({
            column: stringColumn
        })
        expect(wrapper.find('button').exists()).toBeTruthy()
        expect(wrapper.find('input').exists()).toBeFalsy()
        expect(wrapper.find('select').exists()).toBeFalsy()
    })

    test("emits 'save' with 'None' and shows select immediately for status column on mount", async () => {
        wrapper = mountComponent({
            column: statusColumn,
            options: statusOptions
        })
        expect(wrapper.emitted('save')).toBeTruthy()
        expect(wrapper.emitted('save')[0]).toEqual(["None", statusColumn, defaultOrder])
        expect(wrapper.find('button').exists()).toBeTruthy()
        expect(wrapper.find('select').exists()).toBeFalsy()
    })

    test("saves value from select on change and shows button", async () => {
        wrapper = mountComponent({
            column: statusColumn,
            options: statusOptions
        })
        expect(wrapper.emitted('save')).toHaveLength(1)
        expect(wrapper.emitted('save')[0]).toEqual(["None", statusColumn, defaultOrder])

        await wrapper.find('button').trigger('click')

        const select = wrapper.find('select')
        await select.setValue("In Progress")

        expect(wrapper.emitted('save')).toHaveLength(2)
        expect(wrapper.emitted('save')[1]).toEqual(["In Progress", statusColumn, defaultOrder])
        expect(wrapper.find('button').exists()).toBeTruthy()
        expect(wrapper.find('select').exists()).toBeFalsy()
    })

    test("populates select with provided options", async () => {
        wrapper = mountComponent({
            column: statusColumn,
            options: statusOptions,
            isNewRow: true
        })
        const optionElements = wrapper.findAll('select option')
        expect(optionElements.length).toBe(statusOptions.length)
        statusOptions.forEach((option, index) => {
            expect(optionElements[index].text()).toBe(option.value)
            expect(optionElements[index].element.value).toBe(option.value)
        })
    })
})
